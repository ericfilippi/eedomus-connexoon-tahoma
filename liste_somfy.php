<?php
// script créé par Patrice Gauchon pour eedomus
// Version 3.0.0 développée par TeamListeSomfy / 27 mai 2021 
// Version 20210624 23:00

$api_url = 'https://www.tahomalink.com/enduser-mobile-web/enduserAPI/';

$devicesControllableNames = array(
	// 'homekit:StackComponent' => 'interface home kit', pas d'intérêt pour l'instant
	// 'io:StackComponent' => 'stack IO', à vérifier en catégorie 
	// 'ogp:Bridge' => 'interface ogp', pas d'intérêt pour l'instant
	// 'rts:Generic4TRTSComponent' => 'boitier commande RTS', à conserver en catégorie non reconue
	// 'upnpcontrol:SonosPlayOneComponent' => 'sonos', Plugin existant SONOS
	// 'zwave:NodeComponent' => 'noeud zave', pas d'intérêt pour l'instant
	// 'zwave:TransceiverZWaveComponent' => 'transmetteur zwave', pas d'intérêt pour l'instant
	'eliot:OnOffLightEliotComponent' => 'interrupteur Legrand',
	'eliot:OnOffSwitchEliotComponent' => 'prise Legrand',
	'hue:HueLuxHUEComponent' => 'lampe Hue',
	'hue:ColorTemperatureLightCandleHUEComponent' => 'lampe Hue',
	'hue:GenericExtendedColorLightHUEComponent' => 'lampe Hue',
	'internal:PodV2Component' => 'bouton actif',
	'internal:TSKAlarmComponent' => 'télécommande Tahoma',
	'io:AtlanticElectricalHeaterIOComponent' => 'Fil Pilote IO',
	'io:AtlanticElectricalHeaterIOComponent' => 'Interface Fil Pilote IO',
	'io:AtlanticPassAPCDHWComponent' => 'Chauffe-eau IO',
	'io:AtlanticPassAPCHeatingAndCoolingZoneComponent' => 'Commande de chauffage IO',
	'io:AtlanticPassAPCHeatPumpMainComponent' => 'Pompe à chaleur IO',
	'io:AtlanticPassAPCOutsideTemperatureSensor' => 'Capteur de température IO',
	'io:AtlanticPassAPCZoneTemperatureSensor' => 'Capteur de température IO',
	'io:DHWElectricalEnergyConsumptionSensor' => 'Compteur électrique chauffe-eau IO',
	'io:DHWRelatedElectricalEnergyConsumptionSensor' => 'Compteur électrique chauffe-eau IO',
	'io:DiscreteGarageOpenerIOComponent' => 'porte ou portail',
	'io:DomesticHotWaterTankComponent' => 'Résèrve eau chaude pompe à chaleur IO',
	'io:ElectricityMeterComponent' => 'Compteur électrique tableau IO',
	'io:EnergyConsumptionSensorsConfigurationComponent' => 'Compteur électrique divers IO',
	'io:EnergyConsumptionSensorsHeatPumpComponent' => 'Compteur électrique pompe à chaleur IO',
	'io:ExteriorVenetianBlindIOComponent' => 'Store vénitien IO',
	'io:GarageOpenerIOComponent' => 'porte ou portail IO',
	'io:HeatingElectricalEnergyConsumptionSensor' => 'Compteur électrique chauffage IO',
	'io:HeatingRelatedElectricalEnergyConsumptionSensor' => 'Compteur électrique chauffage IO',
	'io:HorizontalAwningIOComponent' => 'volet IO', 
	'io:LightIOSystemSensor' => 'capteur IO', 
	'io:LightMicroModuleSomfyIOComponent' => 'commande lampe IO', 
	'io:MicroModuleRollerShutterSomfyIOComponent' => 'volet IO',
	'io:OnOffIOComponent' => 'Prise On Off IO',
	'io:OtherElectricalEnergyConsumptionSensor' => 'Compteur électrique autre IO',
	'io:PlugsElectricalEnergyConsumptionSensor' => 'Compteur électrique prises IO',
	'io:RollerShutterGenericIOComponent' => 'volet IO',
	'io:RollerShutterVeluxIOComponent' => 'volet IO',
	'io:RollerShutterWithLowSpeedManagementIOComponent' => 'volet IO',
	'io:SlidingDiscreteGateOpenerIOComponent' => 'porte ou portail IO',
	'io:SomfyContactIOSystemSensor' => 'contact ouverture IO',
	'io:SomfyIndoorSimpleSirenIOComponent' => 'sirene IO', 
	'io:SomfyOccupancyIOSystemSensor' => 'detecteur mouvement IO',
	'io:SomfySmokeIOSystemSensor' => 'detecteur fumee IO', 
	'io:TemperatureIOSystemSensor' => 'capteur IO',
	'io:TotalElectricalEnergyConsumptionSensor' => 'Compteur énergie électrique IO',
	'io:TotalElectricEnergyConsumptionIOSensor' => 'Compteur énergie électrique IO',
	'io:WindowOpenerVeluxIOComponent' => 'volet IO',
	'myfox:LightController' => 'camera (light)',
	'myfox:SomfyProtectAlarmController' => 'surveillance camera',
	'myfox:SomfyProtectSecurityCameraController' => 'camera',
	'rtd:AlarmRTDComponent' => 'alarme protexiom',
	'rtds:RTDSContactSensor' => 'contact ouverture RTDS',
	'rtds:RTDSMotionSensor' => 'detecteur mouvement RTDS',
	'rtds:RTDSRemoteControllerComponent' => 'telecommande alarme RTDS',
	'rtds:RTDSSmokeSensor' => 'detecteur fumee RTDS',
	'rts:GarageDoor4TRTSComponent' => 'boitier commande RTS', 
	'rts:GateOpenerRTS4TComponent' => 'porte ou portail RTS', 
	'rts:LightRTSComponent' => 'commande lampe RTS',
	'rts:OnOffRTSComponent' => 'prise RTS', 
	'rts:RollerShutterRTSComponent' => 'volet RTS', 
	'rts:SwingingShutterRTSComponent' => 'volet RTS',
	'rts:SwingingShutterRTSComponent' => 'volet RTS',
	'somfythermostat:SomfyThermostatTemperatureSensor' => 'capteur temperature',
	'somfythermostat:SomfyThermostatHumiditySensor' => 'capteur humidite',
);

$action = getArg('action', false);				// commande à envoyer à la box
$eeDevices = loadVariable('eeDevices');			// Liste des couples deviceUrl (somfy) / periphId (eedomus)
$registerId = loadVariable('registerId');		// id d'abonnement aux événements somfy
$MasterDataSomfy = loadVariable('MasterDataSomfy');	// sauvegarde du statut du MasterData somfy
$countProtect = loadVariable('countProtect');	// Protection contre les trop nombreux logins

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
	
	if ($countProtect < 3)
	{
		$answerLogin = sdk_make_request('login', 'POST', $data);
	}
	else
	{
		$answerLogin = array();
	}

	if (($answerLogin['success'] == 'true') && ($answerLogin['roles'][0]['name'] == 'ENDUSER'))
	{
		$countProtect = 0;
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
		$countProtect++;
	}
	
	saveVariable('countProtect', $countProtect);

	return $resultLogin;
}

// Récupère les gateways, les pièces et les périphériques
function sdk_get_setup($eeDevices)
{
	sdk_make_request('setup/devices/states/refresh', 'POST');

	// On récupère toutes les informations gateways, devices, zones, disconnectionConfiguration, rootPlace et features
	$setup = sdk_make_request('setup', 'GET');

	// Les gateways
	$gateways = array();
	foreach ($setup['gateways'] as $gateway)
	{

	// masque de vérification pour extraire les gateways seulement (pas les catégories: devices, zones, disconnectionConfiguration, rootPlace et features)

		if (preg_match('%^(.*?)-(.*?)$%', $gateway['gatewayId']))
		{
			$gateway_id = $gateway['gatewayId'];
			$gateways[$gateway_id]['id'] = $gateway_id;
			$gateways[$gateway_id]['type'] = $gateway['type'];
			$gateways[$gateway_id]['mode'] = $gateway['mode'];
			
			if (($eeDevices != '') && ($gateway_id == $eeDevices['MasterDataSomfy']['MasterDataSomfyURL']))
			{	// c'est la gateway principale
				$statutGatewayPrincpale = $gateway['alive'];
			}
		}
	}

	// Les devices
	$devices = array();
	foreach ($setup['devices'] as $device)
	{
		$device_url = $device['deviceURL'];
		$devices[$device_url]['url'] = $device_url;
		$devices[$device_url]['label'] = $device['label'];
		$devices[$device_url]['controllableName'] = $device['controllableName'];
		if (isset($device['states'])) {
			foreach ($device['states'] as $state)
			{	// compatibilité ancienne version
				if (preg_match('%^(core|io|internal|myfox|rtds):(.*?)State$%', $state['name'], $match))
				{
					$devices[$device_url]['states'][$match[2]] = $state['value'];
				}
				// nouvelle version
				$devices[$device_url]['coreStates'][$state['name']] = $state['value'];
			}
		}
		// récupération des commandes API et états
		$devices[$device_url]['definition']['commands'] = $device['definition']['commands'];
		$devices[$device_url]['definition']['states'] = $device['definition']['states'];
	}

	return array(
		'devices' => $devices,
		'alive' => $statutGatewayPrincpale,
		'gateways' => $gateways,
	);
}

// Récupère l'état d'un Gateway
function sdk_getGatewayStatus($deviceUrl)
{   
	$commande = 'setup/gateways/' . urlencode($deviceUrl);
	$deviceStates = sdk_make_request($commande, 'GET');
		
	return $deviceStates;
}

// Récupère l'état d'un périphérique
function sdk_getDeviceStatus($deviceUrl)
{   
	$commande = 'setup/devices/' . urlencode($deviceUrl) . '/states';
	$deviceStates = sdk_make_request($commande, 'GET');
	
	return $deviceStates;
}

// Mise à jour d'un périphérique eedomus
function sdk_maj_periph($eeDevices,$deviceUrl,$state,$eeValue)
{
	if (in_array($state, array('core:ClosureState','core:SlateOrientationState')))
	{
		$tmpValue = round($eeValue/5)*5;
		if ($eeValue != 0 and $tmpValue == 0) $tmpValue = 5;
		if ($eeValue != 100 and $tmpValue == 100) $tmpValue = 95;
		$eeValue = $tmpValue;
	}
	if ($state == 'all')
	{
		foreach ($eeDevices[$deviceUrl] as $statePeriph)
		{
			if ($eeValue <> 'Connexion')
			{
				setValue($statePeriph['eeDeviceId'], $eeValue,0,1,date('Y-m-d H:i:s'),0);
			}
			else if ($statePeriph['eeDeviceValueType'] <> 'float')
			{
				setValue($statePeriph['eeDeviceId'], $eeValue,0,1,date('Y-m-d H:i:s'),0);
			}
		}
	}
	else
	{
		if ($eeValue <> 'Connexion')
		{
			setValue($eeDevices[$deviceUrl][$state]['eeDeviceId'], $eeValue,0,1,date('Y-m-d H:i:s'),0);
		}
		else if ($eeDevices[$deviceUrl][$state]['eeDeviceValueType'] <> 'float')
		{
			setValue($eeDevices[$deviceUrl][$state]['eeDeviceId'], $eeValue,0,1,date('Y-m-d H:i:s'),0);
		}
	}
}

// Initialisation de l'état des périphériques
function sdk_process_setup($setup,$eeDevices)
{
	$eeResultat = 3;

	$horsPortee = 0;
	
	// Traitements des équipements
	foreach ($setup['devices'] as $device)
	{
		$deviceUrl = $device['url'];

		if (array_key_exists($deviceUrl,$eeDevices))
		{	
			// Cas des équipements avec StatusState
			if ((isset($device['coreStates']['core:StatusState'])) && ($device['coreStates']['core:StatusState'] <> 'available'))
			{
				$horsPortee++;
			}
			
			// Cas des équipements avec SensorDefectState (rien quand OK, et présent quand problème) 
			if ((array_key_exists('core:SensorDefectState',$eeDevices[$deviceUrl])) && (!array_key_exists('core:SensorDefectState',$device['coreStates'])))
			{
				sdk_maj_periph($eeDevices,$deviceUrl,'core:SensorDefectState','noDefect');
			}
			
			// traitement des tous les states de l'équipment
			foreach ($device['coreStates'] as $coreState => $value)
			{
				if (array_key_exists($coreState,$eeDevices[$deviceUrl]))
				{
					// on met à jour les états
					$valeurPassee = ($horsPortee) ? 'Connexion' : $value;
					sdk_maj_periph($eeDevices,$deviceUrl,$coreState,$valeurPassee);
				}
			}
		}
	}
	
	// Traitement des Gatewys
	foreach ($setup['gateways'] as $gateway)
	{
		$gatewayId = $gateway['id'];
		if (array_key_exists($gatewayId,$eeDevices) && array_key_exists('mode:GatewayState',$eeDevices[$gatewayId]))
		{
			sdk_maj_periph($eeDevices,$gatewayId,'mode:GatewayState',$gateway['mode']);
		}
	}

	if ($horsPortee == true)
	{
		$eeResultat = 2;
	}
	saveVariable('horsPortee', $horsPortee);
	
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
function sdk_display_equipements($devices,$gateways,$migration=false)
{
	global $devicesControllableNames;

	echo '<html><head><title>eedomus</title></head><body>';
	
	$known_devices = array();
	$unknown_devices = array();

	echo '<h1>A) Liste des gateways Somfy :</h1>';
	$iKnown = 1;
	foreach ($gateways as $gateway)
	{
		echo '<p><b>' . $iKnown . ') </b> (type: '.$gateway['type'].')</br>adresse de la Gateway [VAR1] : <input onclick="this.select();" type="text" size="100" readonly="readonly" value="'.urlencode($gateway['id']).'"></p>';
		$iKnown++;
	}

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
		echo '<h1>'.'B) Liste des équipements Somfy reconnus (déjà configurés)'.' :</h1>';
		$iKnown = 1;
		foreach ($known_devices as $device)
		{
			if ($migration)
			{
				echo '<h2>' . $iKnown . ') ' . $device['label'].' => (type: '.$device['controllableName'].')</h2><p>Adresse de l\'équipement Somfy [VAR1] : <input onclick="this.select();" type="text" size="40" readonly="readonly" value="'.urlencode($device['url']).'"></p><h3>Liste des états disponibles :</h3>';
				foreach ($device['definition']['states'] as $state)
				{
					echo 'Etat [VAR2]: <input onclick="this.select();" type="text" size="40" readonly="readonly" value="' . $state['qualifiedName'] . '"> <b>type :</b> ' . $state['type'];
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
			}
			else
			{
				echo '<p><b>' . $iKnown . ') ' .$device['label'].'</b> (type: '.$device['type'].')</br>adresse de l\'équipement Somfy [VAR1] : <input onclick="this.select();" type="text" size="40" readonly="readonly" value="'.urlencode($device['url']).'"></p>';
			}
			$iKnown++;
		}
	}

	if (!empty($unknown_devices))
	{
		echo '<h1>'.'C) Liste des équipements Somfy non reconnus (mais probablement compatibles)'.' :</h1>';
		$iUnknown = 1;
		foreach ($unknown_devices as $device)
		{
			echo '<h2>' . $iUnknown . ') ' . $device['label'].' => (type: '.$device['controllableName'].')</h2><p>Adresse de l\'équipement Somfy [VAR1] : <input onclick="this.select();" type="text" size="40" readonly="readonly" value="'.urlencode($device['url']).'"></p><h3>Liste des commandes disponibles :</h3>';
			foreach ($device['definition']['commands'] as $command)
			{
				switch ($command['nparams'])
				{
					case 0 :
						$motParam = ' paramètre';
						$txtListe = 'paramètre';
						$txtParam = '';
						break;
					case 1 :
						$motParam = ' paramètre';
						$txtListe = 'paramètre';
						$txtParam = '<b>&value={' . $txtListe . '}</b>';
						break;
					default :
						$motParam = ' paramètres';
						$txtListe = 'liste séparée par des virgules';
						$txtParam = '<b>&value={' . $txtListe . '}</b>';
				}
				
				echo 'commande <b>&action =</b> <input onclick="this.select();" type="text" size="40" readonly="readonly" value="' . $command['commandName'] . '">  ('  . $command['nparams'] . $motParam . ') ' 					. $txtParam . '<br/>';
			}
			echo '<h3>Liste des états disponibles :</h3>';
			foreach ($device['definition']['states'] as $state)
			{
				echo 'Etat [VAR2]: <input onclick="this.select();" type="text" size="40" readonly="readonly" value="' . $state['qualifiedName'] . '"> <b>type :</b> ' . $state['type'];
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
			saveVariable('countProtect', 0);
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

		$setup = sdk_get_setup($eeDevices);

		if (!count($setup['devices']))
		{
			sdk_display_login_form('', 'Aucun périphérique détecté.');
		}

		sdk_display_equipements($setup['devices'],$setup['gateways']);
		break;
	case 'reset' :
		//------------------------------------
		// Remise à zéro des données
		//------------------------------------
		$eeDevices = '';
		saveVariable('eeDevices', $eeDevices);
		$MasterDataSomfy['valeur'] = '';
		saveVariable('MasterDataSomfy', $MasterDataSomfy);
		saveVariable('countProtect', 0);
		echo 'Reset effectué';
		break;
	case 'display' :
		//------------------------------------
		// Utilisé pour les tests
		//------------------------------------
		//$tesID = getArg('eedomus_controller_module_id');
		//$priphValeur = getPeriphList(true, $tesID);
		echo '<br/>$eeDevices = <pre>';  var_dump($eeDevices) ; echo '</pre>';
		//$setup = sdk_get_setup($eeDevices);
		//sdk_display_equipements($setup['devices'],$setup['gateways']);
		
		break;
	case 'migration' :
		//------------------------------------
		// Utilisé pour la migration en V3
		//------------------------------------
		
		// Traitement des actions POST
		if (isset($_POST['submit']))
		{
			saveVariable('countProtect', 0);
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
		
		$setup = sdk_get_setup($eeDevices);
		sdk_display_equipements($setup['devices'],$setup['gateways'],true);
		break;
		
	case 'track' :
		//------------------------------------
		// Utilisé pour les tracker le nombre de devices
		//------------------------------------
		$track_counter = count($eeDevices);
		$xml = '<?xml version="1.0" encoding="ISO-8859-1"?><somfy><compteur>'.$track_counter.'</compteur></somfy>';
		echo $xml;
		break;
	case 'getState' :
		//--------------------------------------
		// Compatibilité retour d'état version 1
		//--------------------------------------
		$resultRefresh =  sdk_make_request('setup/devices/states/refresh', 'POST');
		if (array_key_exists('error',$resultRefresh))
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
			$setup = sdk_get_setup($eeDevices);
			sdk_header('text/xml');
			$xml = '<?xml version="1.0" encoding="ISO-8859-1"?>';
			$xml .= '<connexoon>';
			
			foreach ($setup['gateways'] as $gateway)
			{
				$xml .= '<gateway Id="'.$gateway['id'].'" type="'.$gateway['type'].'">';
				$xml .= '<mode>'.$gateway['mode'].'</mode></gateway>';
			}
			
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

			$xml .= '</connexoon>';
			echo $xml;
		}
		break;
	case 'getAllStates' :
		//------------------------------------------
		// MasterData SOMFY et retour d'états version 2
		//------------------------------------------
		
		// enregistre le couple device_urls/periph_id courant
		if (!array_key_exists('MasterDataSomfy',$eeDevices))
		{	
			$device_urls = explode(',', getArg('devices', false));		// les périphériques à traiter
			$deviceId = getArg('eedomus_controller_module_id');			// L'id du prériphérique edomus en cours
			
			$eeDevices['MasterDataSomfy'] = array (
												'eeDeviceId' => $deviceId,
												'MasterDataSomfyURL' => $device_urls[0],
												);
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
				$setup = sdk_get_setup($eeDevices);
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
		{	// le fetch s'est bien passé, on reprend la valeur sauvegardée du MasterData somfy ou on la recalcule
			if ($MasterDataSomfy['valeur'] <> '')
			{
				$eeResultat = $MasterDataSomfy['valeur'];
			}
			else
			{
				$setup = sdk_get_setup($eeDevices);
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
						if ($evenement['gatewayId'] == $eeDevices['MasterDataSomfy']['MasterDataSomfyURL'])
						{
							$setup = sdk_get_setup($eeDevices);
							$eeResultat = sdk_process_setup($setup,$eeDevices);
						}
						break;
					case 'GatewayDownEvent' :
						if ($evenement['gatewayId'] == $eeDevices['MasterDataSomfy']['MasterDataSomfyURL'])
						{
							$eeResultat = 1;
							foreach ($eeDevices as $eeName => $eeDevice)
							{
								if ($eeName <> 'MasterDataSomfy')
								{
									foreach ($eeDevice as $statePeriph)
									{	
										if ($statePeriph['eeDeviceValueType'] <> 'float')
										{
											setValue($statePeriph['eeDeviceId'], 'Connexion',0,1,date('Y-m-d H:i:s'),0);
										}
									}
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
										$deviceStates = sdk_getDeviceStatus($execCommand['deviceURL']);
										if (array_key_exists($execCommand['deviceURL'],$eeDevices))
										{	
											// traitement des tous les states de l'équipment
											foreach ($deviceStates as $key => $state)
											{
												if (array_key_exists($state['name'],$eeDevices[$deviceUrl]))
												{
													// on met à jour les états
													sdk_maj_periph($eeDevices,$deviceUrl,$state['name'],$state['value']);
												}
											}
										}
										
									}
									else
									{
										$deviceUrl = $execCommand['deviceURL'];
										foreach ($eeDevices[$deviceUrl] as $stateKey => $stateItems)
										{
											if ($execCommand['command'] == $stateItems['eeDeviceCommandName'])
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
						$horsPortee = loadVariable('horsPortee');
						$horsPortee++;
						saveVariable('horsPortee', $horsPortee);
						$eeResultat = 2;
						$deviceUrl = $evenement['deviceURL'];
						if (array_key_exists($deviceUrl,$eeDevices))
						{
							sdk_maj_periph($eeDevices,$deviceUrl,'all','Connexion');
						}
						break;
					case 'DeviceAvailableEvent' :
						$horsPortee = loadVariable('horsPortee');
						$horsPortee = ($horsPortee == 0) ? 0 : --$horsPortee;
						saveVariable('horsPortee', $horsPortee);
						$eeResultat = ($horsPortee) ? 2 : 3;
						$deviceUrl = $evenement['deviceURL'];
						$deviceStates = sdk_getDeviceStatus($deviceUrl);
						if (array_key_exists($deviceUrl,$eeDevices))
						{	
							// traitement des tous les states de l'équipment
							foreach ($deviceStates as $key => $state)
							{
								if (array_key_exists($state['name'],$eeDevices[$deviceUrl]))
								{
									// on met à jour les états
									sdk_maj_periph($eeDevices,$deviceUrl,$state['name'],$state['value']);
								}
							}
						}
						break;

				}
			}
		}
		$MasterDataSomfy['valeur'] = $eeResultat;
		saveVariable('MasterDataSomfy', $MasterDataSomfy);

		// construction du résultat
		// 0 : cloud injoignable
		// 1 : cloud OK, box injoignable
		// 2 : un des devices est injoignable
		// 3 : tout est OK
		$xml = '<?xml version="1.0" encoding="ISO-8859-1"?><somfy><resultat>'.$eeResultat.'</resultat></somfy>';
		echo $xml;
		break;
	case 'auto' :
		//------------------------------------------------------------------------
		// remise en statut auto des actionneurs http multiples et actionneurs RTS
		//------------------------------------------------------------------------
		$xml = '<?xml version="1.0" encoding="ISO-8859-1"?><somfy><resultat>unknow</resultat><Timestamp>'.time().'</Timestamp></somfy>';
		echo $xml;
		break;
	case 'init' :
		//------------------------------------------------------------------------
		// Initialisation des capteurs HTTP et récupération de leur état
		//------------------------------------------------------------------------
		$device_urls = explode(',', getArg('devices', false));		// les périphériques à traiter
		$deviceEtat = getArg('etat', false);						// Etat à traiter pour le retour d'état
		$deviceId = getArg('eedomus_controller_module_id');			// L'id du prériphérique edomus en cours
		$deviceData = getPeriphList(true, $deviceId); 				// les paramètres du périphérique
		$deviceValueType = $deviceData[$deviceId]['value_type'];
		$deviceName = $deviceData[$deviceId]['full_name'];
		$value = getArg('value', false) ;
		// enregistre le couple device_urls/periph_id courant
		if ((count($device_urls) == 1) && ($deviceEtat <> ''))
		{
			$eeDevices[$device_urls[0]][$deviceEtat] = array (
														'eeDeviceId' => $deviceId,
														'eeDeviceName' => $deviceName,
														'eeDeviceCommandName' => $action,
														'eeDeviceValueType' => $deviceValueType,
														);
			saveVariable('eeDevices', $eeDevices);
			if ($deviceEtat<> 'mode:GatewayState')
			{
				$deviceStates = sdk_getDeviceStatus($device_urls[0]);
				if (array_key_exists('error',$deviceStates))
				{
					if (sdk_login() != 'ERROR_LOGIN')
					{
						$deviceStates = sdk_getDeviceStatus($device_urls[0]);
					}
				}
				$trouve = 0;
				foreach ($deviceStates as $deviceState)
				{
					if ($deviceState['name'] == $deviceEtat)
					{
						$xml = '<?xml version="1.0" encoding="ISO-8859-1"?><somfy><state>' . $deviceState['value'] . '</state></somfy>';
						$trouve++;
						echo $xml;
					}
				}
				// gestion des piles pour l'absence de core:SensorDefectState
				if ((!$trouve) && ($deviceEtat == 'core:SensorDefectState'))
				{
					$xml = '<?xml version="1.0" encoding="ISO-8859-1"?><somfy><state>noDefect</state></somfy>';
					echo $xml;
				}
			}
			else
			{
				$deviceStates = sdk_getGatewayStatus($device_urls[0]);
				if (array_key_exists('error',$deviceStates))
				{
					if (sdk_login() != 'ERROR_LOGIN')
					{
						$deviceStates = sdk_getGatewayStatus($device_urls[0]);
					}
				}
				$xml = '<?xml version="1.0" encoding="ISO-8859-1"?><somfy><gateway>' . $deviceStates['mode'] . '</gateway></somfy>';
				echo $xml;
			}
		}
		break;
	default :
		//------------------------------------------
		// Traitement des commandes
		//------------------------------------------
		$device_urls = explode(',', getArg('devices', false));		// les périphériques à traiter
		$deviceEtat = getArg('etat', false);						// Etat à traiter pour le retour d'état
		$deviceId = getArg('eedomus_controller_module_id');			// L'id du prériphérique edomus en cours
		$deviceData = getPeriphList(true, $deviceId); 				// les paramètres du périphérique
		$deviceValueType = $deviceData[$deviceId]['value_type'];
		$deviceName = $deviceData[$deviceId]['full_name'];
		$value = getArg('value', false) ;

		// enregistre le couple device_urls/periph_id courant
		if ((count($device_urls) == 1) && ($deviceEtat <> ''))
		{
			$eeDevices[$device_urls[0]][$deviceEtat] = array (
														'eeDeviceId' => $deviceId,
														'eeDeviceName' => $deviceName,
														'eeDeviceCommandName' => $action,
														'eeDeviceValueType' => $deviceValueType,
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
			$valueTableau = explode (',',$value);
			foreach ($valueTableau as $tKey => $tValue)
			{
				if (!is_numeric($tValue))
				{ $valueTableau[$tKey] = '"'.$tValue.'"';}
			}
			$value = implode($valueTableau,',');
		
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
