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
	'io:ExteriorVenetianBlindIOComponent' => 'BSO IO',
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
			}
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
		foreach ($eeDevices[$deviceUrl] as $statePeriphId)
		{
			setValue($statePeriphId, $eeValue,0,1,date('Y-m-d H:i:s'),0);
		}
	}
	else
	{
		setValue($eeDevices[$deviceUrl][$state], $eeValue,0,1,date('Y-m-d H:i:s'),0);
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
			if (array_key_exists('SlateOrientation',$eeDevices[$deviceUrl]))
			{
				$valeurPassee = ($horsPortee) ? 200 : $device['states']['SlateOrientation'];
				sdk_maj_periph($eeDevices,$deviceUrl,'SlateOrientation',$valeurPassee);
			}
			if (array_key_exists('Closure',$eeDevices[$deviceUrl]))
			{
				$valeurPassee = ($horsPortee) ? 200 : $device['states']['Closure'];
				sdk_maj_periph($eeDevices,$deviceUrl,'Closure',$valeurPassee);
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
		echo '<p><b>'.'Liste des périphériques'.' :</b></p>';
		foreach ($known_devices as $device)
		{
			echo '<p><b>'.$device['label'].'</b> (type: '.$device['type'].')<br><input onclick="this.select();" type="text" size="40" readonly="readonly" value="'.$device['url'].'"></p>';
		}
	}

	if (!empty($unknown_devices))
	{
		echo '<p><b>'.'Liste des périphériques non reconnus (mais probablement compatibles)'.' :</b></p>';
		foreach ($unknown_devices as $device)
		{
			echo '<p><b>'.$device['label'].'</b> => (type: '.$device['controllableName'].')<br><input onclick="this.select();" type="text" size="40" readonly="readonly" value="'.$device['url'].'"></p>';
		}
	}
	echo '</body>';
	die;
}

//------------------------------
// Page de configuration
//------------------------------

if (empty($action))
{
	if (empty($_POST['submit']))
	{
		sdk_display_login_form('Veuillez renseigner vos identifiants Somfy.');
	}

	saveVariable('username', $_POST['username']);
	saveVariable('password', $_POST['password']);

	$result = sdk_login();

	if (isset($result['error']) and $result['error'] == 'Bad credentials')
	{
		sdk_display_login_form('', 'Identifiants de connexion incorrects');
	}
	if (!isset($result['success']) and !$result['success'])
	{
		sdk_display_login_form('', 'Impossible de se connecter au serveur Somfy');
	}

	$setup = sdk_get_setup();

	if (!count($setup['devices']))
	{
		sdk_display_login_form('', 'Aucun périphérique détecté.');
	}

	sdk_display_devices($setup['devices']);
}

//------------------------------
// Actions
//------------------------------

if (in_array($action, array('setClosure','setOrientation','setClosureAndOrientation','close','open','up','down','stop')))
{
	$device_urls = explode(',', getArg('devices'));

	// enregistre le couple device_urls/periph_id courant
	$eeAction = ($action == 'setOrientation') ? 'SlateOrientation' : 'Closure' ;
	if ((count($device_urls) == 1) && (!array_key_exists($device_urls[0][$eeAction],$eeDevices)))
	{
		$eeDevices[$device_urls[0]][$eeAction] = getArg('eedomus_controller_module_id');
		saveVariable('eeDevices', $eeDevices);
	}

	// Set Closure et setOrientation
	if ((in_array($action, array('setClosure','setOrientation','setClosureAndOrientation'))))
	{
		$value = getArg('value') ;
		$commands[$action] = $value;
		$path='exec/apply';
	}
	// Portail
	if (in_array($action, array('close','open','up','down','stop')))
	{
		$commands[$action] = null;
		$path='exec/apply/highPriority';
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

//------------------------------
// Polling (XML)
//------------------------------

if ($action == 'getState')
{
	sdk_login();
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

//------------------------------
// Polling (XML)
//------------------------------

if ($action == 'getAllStates')
{
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
							foreach ($eeDevice as $statePeriphId)
							{
								setValue($statePeriphId, 200,0,1,date('Y-m-d H:i:s'),0);
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
							if ((array_key_exists('SlateOrientation',$eeDevices[$deviceUrl])) && ($deviceState['name'] == 'core:SlateOrientationState'))
							{
								$states['SlateOrientation'] = $deviceState['value'];
								sdk_maj_periph($eeDevices,$deviceUrl,'SlateOrientation',$deviceState['value']);
							}
							elseif ((array_key_exists('Closure',$eeDevices[$deviceUrl])) && ($deviceState['name'] == 'core:ClosureState'))
							{
								$states['Closure'] = $deviceState['value'];
								sdk_maj_periph($eeDevices,$deviceUrl,'Closure',$deviceState['value']);
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
									if ((array_key_exists('SlateOrientation',$eeDevices[$deviceUrl])) && ($execCommand['command'] == 'setOrientation'))
									{
										sdk_maj_periph($eeDevices,$deviceUrl,'SlateOrientation',300);
									}
									elseif ((array_key_exists('Closure',$eeDevices[$deviceUrl])) && ($execCommand['command'] == 'setClosure'))
									{
										sdk_maj_periph($eeDevices,$deviceUrl,'Closure',300);
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
						sdk_maj_periph($eeDevices,$deviceUrl,'all',200);
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
}

if ($action == 'auto')
{
	// remise en statut auto des actionneurs http multiples
    $xml = '<?xml version="1.0" encoding="ISO-8859-1"?><connexoon><resultat>0</resultat><Timestamp>'.time().'</Timestamp></connexoon>';
	echo $xml;
}

if ($action == 'reset')
{
	// remise à zéro des donnée sauvegardées
	$eeDevices = '';
	saveVariable('eeDevices', $eeDevices);
	$capteurSomfy['valeur'] = '';
	saveVariable('capteurSomfy', $capteurSomfy);
	echo 'Reset effectué';
}

if ($action == 'display')
{
	// remise à zéro des donnée sauvegardées
	$resultCommand = sdk_json_decode( '{"execution":{"eventTime":1604217728873,"owner":"herricdumont@yahoo.fr","source":"mobile:tool","endTime":1604217729503,"effectiveStartTime":1604217728873,"duration":630,"id":"82d3ef67-ac10-3e01-1542-fa07de069b0a","label":"eedomus command","type":"Immediate execution - MANUAL_CONTROL","state":"FAILED","failureType":"NONEXEC_OTHER","commands":[{"deviceURL":"io://1225-4383-2128/12250793","command":"setOrientation","parameters":[20],"rank":0,"dynamic":false,"state":"FAILED","failureType":"NONEXEC_OTHER"}],"executionType":"Immediate execution","executionSubType":"MANUAL_CONTROL"}}');
	echo 'failure type = ' . $resultCommand['execution']['commands'][0]['failureType'] . ' ### ';
	echo 'failure type = ' . $resultCommand['execution']['commands'][0]['failureType'] . ' ### ';
}

?>
