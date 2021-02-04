<?php
// script créé par Patrice Gauchon pour eedomus
// Version 2.0.0 / 27 juin 2020

$api_url = 'https://www.tahomalink.com/enduser-mobile-web/enduserAPI/';

$devicesControllableNames = array(
	'io:RollerShutterVeluxIOComponent' => 'volet IO',
	'io:RollerShutterGenericIOComponent' => 'volet IO',
	'io:RollerShutterWithLowSpeedManagementIOComponent' => 'volet IO',
	'io:WindowOpenerVeluxIOComponent' => 'volet IO',
	'io:DiscreteGarageOpenerIOComponent' => 'porte ou portail',
	'rts:SwingingShutterRTSComponent' => 'volet RTS',
	'io:ExteriorVenetianBlindIOComponent' => 'Store vénitien IO',
);

$action = getArg('action', false);				// commande à envoyer à la box
$eeDevices = loadVariable('eeDevices');			// Liste des couples deviceUrl (somfy) / periphId (eedomus)
$registerId = loadVariable('registerId');		// id d'abonnement aux événements somfy
$capteurSomfy = loadVariable('capteurSomfy');	// sauvegarde du statut du capteur somfy

//------------------------------
// Fonctions
//------------------------------

// Envoi une requête à l'API
function sdk_make_request($path, $method='POST', $data=NULL, $content_type=NULL)
{
	global $api_url;

	$header = NULL;
	if ($content_type == 'json')
	{
		$header = array('Content-Type: application/json');
	}
	else if (!empty($data))
	{
		$data = http_build_query($data);
	}

	$result = httpQuery($api_url.$path, $method, $data, NULL, $header, true);

	return sdk_json_decode($result);
}

// Login et abonnement aux événements somfy
function sdk_login()
{
	$data = array(
		'userId' => loadVariable('username'),
		'userPassword' => loadVariable('password'),
	);

	$answerLogin = sdk_make_request('login', 'POST', $data);

	if (($answerLogin['success'] == 'true') && ($answerLogin['roles'][0]['name'] == 'ENDUSER'))
	{
	    $answerRegister = sdk_make_request('events/register', 'POST');

	    if (array_key_exists('id',$answerRegister))
	    {
	        $registerId = $answerRegister['id'];
	        saveVariable('registerId', $registerId);
	    }
	    else
	    {
	        $resultLogin = 'ERROR_LOGIN';
	    }
	}
	else
	{
	    $resultLogin = 'ERROR_LOGIN';
	}

	return $resultLogin;
}

// Récupère les pièces et les périphériques
function sdk_get_setup()
{
	sdk_make_request('setup/devices/states/refresh', 'POST');

	// On récupère les périphériques
	$setup = sdk_make_request('setup', 'GET');

	$devices = array();
	foreach ($setup['devices'] as $device)
	{
		if (preg_match('%^(io|rts)://.*?/\d*?$%', $device['deviceURL']))
		{
			$device_url = $device['deviceURL'];

			$devices[$device_url]['url'] = $device_url;
			$devices[$device_url]['label'] = $device['label'];
			$devices[$device_url]['controllableName'] = $device['controllableName'];

			foreach ($device['states'] as $state)
			{
				if (preg_match('%^(core|io):(.*?)State$%', $state['name'], $match))
				{
					$devices[$device_url]['states'][$match[2]] = $state['value'];
				}
				$devices[$device_url]['coreStates'][$state['name']] = $state['value'];
			}
			// récupération des commandes API et états
			$devices[$device_url]['definition']['commands'] = $device['definition']['commands'];
			$devices[$device_url]['definition']['states'] = $device['definition']['states'];
		}
	}

	return array(
		'devices' => $devices,
		'time' => time(),
		'alive' => $setup['gateways'][0]['alive'],
	);
}

// Mise à jour d'un périphérique eedomus
function sdk_maj_periph($eeDevices,$deviceUrl,$state,$eeValue)
{
	$tmpValue = round($eeValue/5)*5;
	if ($eeValue != 0 and $tmpValue == 0) $tmpValue = 5;
	if ($eeValue != 100 and $tmpValue == 100) $tmpValue = 95;
	$eeValue = $tmpValue;
	if ($state == 'all')
	{
		foreach ($eeDevices[$deviceUrl] as $statePeriph)
		{
			setValue($statePeriph['eeDeviceId'], $eeValue,0,1,date('Y-m-d H:i:s'),0);
		}
	}
	else
	{
		setValue($eeDevices[$deviceUrl][$state]['eeDeviceId'], $eeValue,0,1,date('Y-m-d H:i:s'),0);
	}
}

// Initialisation de l'état des périphériques
function sdk_process_setup($setup,$eeDevices)
{
	$eeResultat = 3;

	$horsPortee = false;
	foreach ($setup['devices'] as $device)
	{
		$deviceUrl = $device['url'];

		if (array_key_exists($deviceUrl,$eeDevices))
		{
			if ($device['states']['Status'] <> 'available')
			{
				$horsPortee = true;
			}
			foreach ($device['coreStates'] as $coreState => $value)
			{
				if (array_key_exists($coreState,$eeDevices[$deviceUrl]))
				{
					$valeurPassee = ($horsPortee) ? 'Connexion' : $value;
					sdk_maj_periph($eeDevices,$deviceUrl,$coreState,$valeurPassee);
				}
			}
		}
	}
	if ($horsPortee == true)
	{
		$eeResultat = 2;
	}

	return $eeResultat;
}

// On applique une commande aux périphériques d'une pièce
function sdk_apply_command($device_urls, $commands, $path='exec/apply')
{
	$actions = array();
	foreach ($device_urls as $device_url)
	{
		$commands_str = array();
		foreach ($commands as $command_name => $value)
		{
		    if ($value === null)
		    {
			    $commands_str[] = '{"name":"'.$command_name.'","parameters":[]}';
		    }
		    else
		    {
		        $commands_str[] = '{"name":"'.$command_name.'","parameters":['.$value.']}';
		    }
		}

		$actions[] = '{"deviceURL":"'.trim($device_url).'","commands":['.implode($commands_str,',').']}';
	}

	$json = '{"label":"eedomus command","notificationTypeMask":"0","notificationCondition":"NEVER","actions":['.implode($actions, ',').']}';

	return sdk_make_request($path, 'POST', $json, 'json');
}

// Ecran de login
function sdk_display_login_form($message='', $error='')
{
	echo '<html><head><title>eedomus</title></head><body>';
	if (!empty($message)) echo '<p><b>'.$message.'</b></p>';
	if (!empty($error)) echo '<p style="color:red"><b>'.$error.'</b></p>';
	echo '<form method="post">';
	echo 'Nom d\'utilisateur Somfy'.' :<br><input type="text" name="username" value="'.loadVariable('username').'"><br><br>';
	echo 'Mot de passe Somfy'.' :<br><input type="password" name="password" value="'.loadVariable('password').'"><br><br>';
	echo '<input type="submit" name="submit" value="'.'Connexion'.'">';
	echo '</body>';
	die;
}

// Ecran des pièces
function sdk_display_devices($devices)
{
	global $devicesControllableNames;

	echo '<html><head><title>eedomus</title></head><body>';
	$known_devices = array();
	$unknown_devices = array();

	echo '<h1>'.'A) Capteur d état SOMFY'.' :</h1>';
	echo '<p>adresse [VAR1] : <input onclick="this.select();" type="text" size="40" readonly="readonly" value="Capteur SOMFY"></p>';

	foreach ($devices as $device_url => $device)
	{
		if (isset($devicesControllableNames[$device['controllableName']]))
		{
			$device['type'] = $devicesControllableNames[$device['controllableName']];
			$known_devices[] = $device;
		}
		else
		{
			$unknown_devices[] = $device;
		}
	}

	if (!empty($known_devices))
	{
		echo '<h1>'.'B) Liste des périphériques reconnus'.' :</h1>';
		$iKnown = 1;
		foreach ($known_devices as $device)
		{
			echo '<p><b>' . $iKnown . ') ' .$device['label'].'</b> (type: '.$device['type'].')</br>adresse [VAR1] : <input onclick="this.select();" type="text" size="40" readonly="readonly" value="'.$device['url'].'"></p>';
			$iKnown++;
		}
	}

	if (!empty($unknown_devices))
	{
		echo '<h1>'.'C) Liste des périphériques non reconnus (mais probablement compatibles)'.' :</h1>';
		$iUnknown = 1;
		foreach ($unknown_devices as $device)
		{
			echo '<h2>' . $iUnknown . ') ' . $device['label'].' => (type: '.$device['controllableName'].')</h2><p>adresse du périphérique [VAR1] : <input onclick="this.select();" type="text" size="40" readonly="readonly" value="'.$device['url'].'"></p><h3>Liste des commandes disponibles :</h3>';
			foreach ($device['definition']['commands'] as $command)
			{
				$txtParam = ($command['nparams'] == 0) ? '' : '<b>&value={liste séparée par des virgules}</b>';
				echo 'commande <b>&action =</b> <input onclick="this.select();" type="text" size="40" readonly="readonly" value="' . $command['commandName'] . '">  '  . $command['nparams'] . ' paramètres) ' 					. $txtParam . '<br/>';
			}
			echo '<h3>liste des états disponibles :</h3>';
			foreach ($device['definition']['states'] as $state)
			{
				echo 'état [VAR2]: <input onclick="this.select();" type="text" size="40" readonly="readonly" value="' . $state['qualifiedName'] . '"> <b>type :</b> ' . $state['type'];
				if (isset($state['values']))
				{
					echo '<b> | valeurs :</b> ';
					foreach ($state['values'] as $valeur)
					{
						echo  $valeur .  ',';
					}
				}
				echo '<br/>';
			}
			echo '</p>';
			$iUnknown++;
		}
	}
	echo '</body>';
	die;
}

//------------------------------------
// traitements en fonction des actions
//------------------------------------

switch ($action)
{
	case '' :
		//------------------------------------
		// Ecran de configuration
		//------------------------------------
		
		// Traitement des actions POST
		if (isset($_POST['submit']))
		{
			saveVariable('username', $_POST['username']);
			saveVariable('password', $_POST['password']);
		}
		
		$resultFetch =  sdk_make_request('events/' . $registerId . '/fetch', 'POST');
		if (array_key_exists('error',$resultFetch))
		{
			$testUser = loadVariable('username');
			if ($testUser == '')
			{
				sdk_display_login_form('Veuillez renseigner vos identifiants Somfy.');
			}
			else
			{
				if (sdk_login() == 'ERROR_LOGIN')
				{	// Erreur d'identification au cloud
					sdk_display_login_form('', 'Identifiants de connexion incorrects');
				}
			}
		}

		$setup = sdk_get_setup();

		if (!count($setup['devices']))
		{
			sdk_display_login_form('', 'Aucun périphérique détecté.');
		}

		sdk_display_devices($setup['devices']);
		break;
	case 'reset' :
		//------------------------------------
		// Remise à zéro des données
		//------------------------------------
		$eeDevices = '';
		saveVariable('eeDevices', $eeDevices);
		$capteurSomfy['valeur'] = '';
		saveVariable('capteurSomfy', $capteurSomfy);
		echo 'Reset effectué';
		break;
	case 'display' :
		//------------------------------------
		// Utilisé pour les tests
		//------------------------------------
		echo '<br/><pre>';  var_dump($eeDevices) ; echo '</pre>';
		$setup = sdk_get_setup();
		sdk_display_devices($setup['devices']);
		break;
	case 'getState' :
		//--------------------------------------
		// Compatibilité retour d'état version 1
		//--------------------------------------
		$resultFetch =  sdk_make_request('events/' . $registerId . '/fetch', 'POST');
		if (array_key_exists('error',$resultFetch))
		{
			if (sdk_login() != 'ERROR_LOGIN')
			{
				$logge = true;
			}
			else
			{
				$logge = false;
			}
		}
		else
		{
			$logge = true;
		}

		if ($logge)
		{
			$setup = sdk_get_setup();
			sdk_header('text/xml');
			$xml = '<?xml version="1.0" encoding="ISO-8859-1"?>';
			$xml .= '<connexoon>';

			foreach ($setup['devices'] as $device)
			{
				$xml .= '<device url="'.$device['url'].'" label="'.$device['label'].'">';

				foreach ($device['states'] as $state => $value)
				{
					if ($state == 'Closure')
					{
						$closure_value = round($value/5)*5;
						if ($value != 0 and $closure_value == 0) $closure_value = 5;
						if ($value != 100 and $closure_value == 100) $closure_value = 95;
						$value = $closure_value;
					}
					if ($state == 'SlateOrientation')
					{
						$SlateOrientation = round($value/5)*5;
						if ($value != 0 and $SlateOrientation == 0) $SlateOrientation = 5;
						if ($value != 100 and $SlateOrientation == 100) $SlateOrientation = 95;
						$value = $SlateOrientation;
					}
					$xml .= '<'.$state.'>'.$value.'</'.$state.'>';
				}

				$xml .= '</device>';
			}

			$xml .= '<Timestamp>'.$setup['time'].'</Timestamp>';
			$xml .= '</connexoon>';
			echo $xml;
		}
		break;
	case 'getAllStates' :
		//------------------------------------------
		// Capteur SOMFY et retour d'états version 2
		//------------------------------------------
		
		// enregistre le couple device_urls/periph_id courant
		if (!array_key_exists('capteurSomfy',$eeDevices))
		{
			$eeDevices['capteurSomfy'] = getArg('eedomus_controller_module_id');
			saveVariable('eeDevices', $eeDevices);
		}

		// Lecture des événements
		$resultFetch =  sdk_make_request('events/' . $registerId . '/fetch', 'POST');
		if (array_key_exists('error',$resultFetch))
		{
			if (sdk_login() == 'ERROR_LOGIN')
			{	// Erreur d'identification au cloud
				$eeResultat = 0;
			}
			else
			{
				$setup = sdk_get_setup();
				if ($setup['alive'] <> 'true')
				{	// La box n'est pas connectée à son cloud
					$eeResultat = 1;
				}
				else
				{
					$eeResultat = sdk_process_setup($setup,$eeDevices);
				}
				$resultFetch =  sdk_make_request('events/' . $registerId . '/fetch', 'POST');
			}
		}
		else
		{	// le fetch s'es bien passé, on reprend la valeur sauvegardée du capteur somfy ou on la recalcule
			if ($capteurSomfy['valeur'] <> '')
			{
				$eeResultat = $capteurSomfy['valeur'];
			}
			else
			{
				$setup = sdk_get_setup();
				if ($setup['alive'] <> 'true')
				{	// La box n'est pas connectée à son cloud
					$eeResultat = 1;
				}
				else
				{
					$eeResultat = sdk_process_setup($setup,$eeDevices);
				}
			}
		}

		if ($eeResultat > 0)
		{
			// On est connecté au cloud et on peut traiter les événements
			foreach ($resultFetch as $evenement)
			{
				switch ($evenement['name'])
				{
					case 'GatewayAliveEvent' :
						$setup = sdk_get_setup();
						$eeResultat = sdk_process_setup($setup,$eeDevices);
						break;
					case 'GatewayDownEvent' :
						$eeResultat = 1;
						foreach ($eeDevices as $eeName => $eeDevice)
						{
							if ($eeName <> 'capteurSomfy')
							{
								foreach ($eeDevice as $statePeriph)
								{
									setValue($statePeriph['eeDeviceId'], 'Connexion',0,1,date('Y-m-d H:i:s'),0);
								}
							}
						}
						break;
					case 'DeviceStateChangedEvent' :	// mise à jour des état des périphériques
						$deviceUrl = $evenement['deviceURL'];
						if (array_key_exists($deviceUrl,$eeDevices))
						{
							foreach ($evenement['deviceStates'] as $deviceState)
							{
								if (array_key_exists($deviceState['name'],$eeDevices[$deviceUrl]))
								{
									sdk_maj_periph($eeDevices,$deviceUrl,$deviceState['name'],$deviceState['value']);
								}
							}
						}
						break;
					case 'ExecutionStateChangedEvent' :		// la commande envoyée revient en erreur
						if ($evenement['newState'] == 'FAILED')
						{
							// recherche de la cause
							$path = 'history/executions/' . $evenement['execId'];
							$resultHistory = sdk_make_request($path, $method='GET');
							foreach ($resultHistory['execution']['commands'] as $execCommand)
							{
								if ($execCommand['state'] == 'FAILED')
								{
									if (in_array($execCommand['failureType'], array('NONEXEC_OTHER')))
									{
										$setup = sdk_get_setup();
										$eeResultat = sdk_process_setup($setup,$eeDevices);
									}
									else
									{
										$deviceUrl = $execCommand['deviceURL'];
										foreach ($eeDevices[$deviceUrl] as $stateKey => $stateItems)
										{
											if (($execCommand['command'] == $stateItems['eeDeviceCommandName']))
											{
												sdk_maj_periph($eeDevices,$deviceUrl,$stateKey,'Erreur');
											}
										}
									}
								}
							}
						}
						break;
					case 'DeviceUnavailableEvent' :
						$eeResultat = 2;
						$deviceUrl = $evenement['deviceURL'];
						if (array_key_exists($deviceUrl,$eeDevices))
						{
							sdk_maj_periph($eeDevices,$deviceUrl,'all','Connexion');
						}
						break;
					case 'DeviceAvailableEvent' :
						$setup = sdk_get_setup();
						$eeResultat = sdk_process_setup($setup,$eeDevices);
						break;

				}
			}
		}
		$capteurSomfy['valeur'] = $eeResultat;
		saveVariable('capteurSomfy', $capteurSomfy);

		// construction du résultat
		// 0 : cloud injoignable
		// 1 : cloud OK, box injoignable
		// 2 : un des devices est injoignable
		// 3 : tout est OK
		$xml = '<?xml version="1.0" encoding="ISO-8859-1"?>';
		$xml .= '<connexoon>';
		$xml .= '<resultat>'.$eeResultat.'</resultat>';
		$xml .= '<Timestamp>'.$setup['time'].'</Timestamp>';
		$xml .= '</connexoon>';
		echo $xml;
		break;
	case 'auto' :
		//------------------------------------------------------------------------
		// remise en statut auto des actionneurs http multiples et actionneurs RTS
		//------------------------------------------------------------------------
		$xml = '<?xml version="1.0" encoding="ISO-8859-1"?><connexoon><resultat>unknow</resultat><Timestamp>'.time().'</Timestamp></connexoon>';
		echo $xml;
		break;
	default :
		//------------------------------------------
		// Traitement des commandes
		//------------------------------------------
		$device_urls = explode(',', getArg('devices', false));		// les périphériques à traiter
		$deviceEtat = getArg('etat', false);						// Etat à traiter pour le retour d'état
		$deviceId = getArg('eedomus_controller_module_id');			// L'id du prériphérique edomus en cours
		$value = getArg('value', false) ;

		// enregistre le couple device_urls/periph_id courant
		if ((count($device_urls) == 1) && ($deviceEtat <> ''))
		{
			$eeDevices[$device_urls[0]][$deviceEtat] = array (
														'eeDeviceId' => $deviceId,
														'eeDeviceCommandName' => $action,
														);
			saveVariable('eeDevices', $eeDevices);
		}

		// Préparation des paramètres de la commande
		if ($value == '')
		{	// pas de paramètre
			$commands[$action] = null;
			$path='exec/apply/highPriority';
		}
		else
		{	// un ou plusieurs paramètres
			$action = ($action == 'setSlateOrientation') ? 'setOrientation' : $action;		// compatibilité version 1
			$commands[$action] = $value;
			$path='exec/apply';
		}

		// Exécution de la commande
		$resultCommand = sdk_apply_command($device_urls, $commands, $path);
		if (array_key_exists('error',$resultCommand))
		{
			if (sdk_login() != 'ERROR_LOGIN')
			{
				$resultCommand = sdk_apply_command($device_urls, $commands, $path);
			}
		}
		if (array_key_exists('execId',$resultCommand))
		{
			$execIds[$resultCommand['execId']] = $device_urls;
		}
}

?>
