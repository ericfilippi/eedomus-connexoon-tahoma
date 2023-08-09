<?php
// Version 3.2.0 développée par TeamListeSomfy -- 02/05/2023 
// Code version 20230502 22:40

$devicesControllableNames = array(
	// 'homekit:StackComponent' => 'interface home kit', pas d'intérêt pour l'instant
	// 'io:StackComponent' => 'stack IO', à vérifier en catégorie 
	// 'ogp:Bridge' => 'interface ogp', pas d'intérêt pour l'instant
	// 'rts:Generic4TRTSComponent' => 'boitier commande RTS', à conserver en catégorie non reconue
	// 'upnpcontrol:SonosPlayOneComponent' => 'sonos', Plugin existant SONOS
	// 'zwave:NodeComponent' => 'noeud zwave', pas d'intérêt pour l'instant
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

$action = getArg('action', false);						// commande à envoyer à la box
$eeDevices = loadVariable('eeDevices');					// Liste des couples deviceUrl (somfy) / periphId (eedomus)
$registerId = loadVariable('registerId');				// id d'abonnement aux événements somfy cloud
$MasterDataSomfy = loadVariable('MasterDataSomfy');		// sauvegarde du statut du MasterData somfy
$execIds = loadVariable('execIds');						// historique des ids d'execution

//----------------------------------------
// Chargement des variables mode local API
//----------------------------------------
global $modeLocal;										// mode local (1) ou mode cloud (vide ou 0)
global $modeLocalToken;									// token local (1) ou non (vide ou 0)
global $adresseMAC;										// adresse MAC de la box Somfy
global $forceModeCloud;									// Mode cloud seulement
global $modeLocalErreur;
$modeLocal = loadVariable('modeLocal');
$modeLocalToken = loadVariable('modeLocalToken');
$adresseMAC = loadVariable('adresseMAC');
$forceModeCloud = 0;
$localToken = loadVariable('localToken');				// le Token pour les requêtes locales
$localRegisterId = loadVariable('localRegisterId');		// id pour les fetch locaux
$modeLocalErreur = array();

//------------------------------
// Fonctions
//------------------------------

// Gestion de la protection API
function sdk_countProtect($control='OK', $countProtect=array())
{
	switch ($control)
	{
		case 'reset' :
			$countReset = array(
							'count' => 0,
							'startTime' => 0,
							'offset' => 0,
							'display' => array(),
							);
			return $countReset;
			break;
		case 'pause' :
			$countReset = array(
							'count' => 4,
							'startTime' => 0,
							'offset' => 0,
							'display' => array(
											4 => 'MasterData en pause',
										),
							);
			return $countReset;
			break;
		case 'OK' :
			if ($countProtect['count'] >= 3)
			{
				// si on est bloqué, on regarde si le timer (offset) est écoulé
				$tempsRestant = $countProtect['startTime'] + $countProtect['offset'] - time();
				if ($tempsRestant <= 0)
				{
					// le timer est écoulé
					$countProtect['count'] = 2;
					saveVariable('countProtect', $countProtect);
					return true;
				}
				else
				{
					// le timer n est pas écoulé
					return false;
				}
			}
			else
			{
				return true;
			}
			break;
	}
}

// Vérifie si on est temporairement en mode cloud et force le login en mode local après 10 pollings
function sdk_checkModeLocal()
{
	global $modeLocal;
	global $modeLocalToken;
	global $adresseMAC;
	
	if ($modeLocal)
	{	// on est en mode local, on laisse passer
		return true;
	}
	else
	{
		if ($adresseMAC == '')
		{	// pas d'adresse MAC on laisse passer
			return true;
		}
		else
		{	// une adresse MAC est renseignée, on retente le mode local quand le timer est  écoulé
			$timerLocal = loadVariable('timerLocal');
			$timerLocal = ($timerLocal == '') ? 0 : $timerLocal;
			if ($timerLocal > 10)
			{	// timer écoulé
				$timerLocal = 0;
				saveVariable('timerLocal', $timerLocal);
				$modeLocal = 1;
				return false;
			}
			else
			{	// timer + 1
				saveVariable('timerLocal', $timerLocal + 1);
				return true;
			}
		}
	}
}


// Envoi une requête à l'API
function sdk_make_request($path, $method='POST', $data=NULL, $content_type=NULL, $token=NULL)
{
	global $modeLocal;
	global $modeLocalToken;
	global $adresseMAC;
	global $modeLocalErreur;
	
	$api_url_cloud = 'https://www.tahomalink.com/enduser-mobile-web/enduserAPI/';
	$api_url_locale_auth = 'https://ha101-1.overkiz.com/enduser-mobile-web/enduserAPI/';
	//$api_url_locale = 'https://gateway-'.$eeDevices['MasterDataSomfy']['MasterDataSomfyURL'].'.local:8443/enduser-mobile-web/1/enduserAPI/';
	if ($adresseMAC != '')
	{
		$api_url_locale = 'https://' . sdk_get_ip_from_ip_or_mac($adresseMAC) . ':8443/enduser-mobile-web/1/enduserAPI/';
	}
	
	if ($modeLocalToken) {$api_url_request = $api_url_locale;}
	else if ($modeLocal) {$api_url_request = $api_url_locale_auth;}
	else {$api_url_request = $api_url_cloud;}
	
	$countProtect = loadVariable('countProtect');	// Protection contre les trop nombreux logins
	
	//$header = array('Authorization: Bearer ' . $answerToken['token']);
	
	if ($content_type == NULL)
	{
		$header = NULL;
	}
	else
	{
		$header == array();
	}
	
	if ($content_type == 'json')
	{
		$header[] = 'accept: application/json';
		$header[] = 'Content-Type: application/json';
	}
	else if ($content_type == 'x-www-form-urlencoded')
	{
		$header[] = 'Content-Type: application/x-www-form-urlencoded';
		if (!empty($data))
		{
			$data = http_build_query($data);
		}
	}
	else if (!empty($data))
	{
		$data = http_build_query($data);
	}

	
	if ($modeLocalToken)
	{
		$header[] = 'Authorization: Bearer ' . $token;
	}

    if ($countProtect['count'] < 3)
	{
		$modeLocalErreur[] = 'Request : url : ' . $api_url_request.$path;
		saveVariable('modeLocalErreur', $modeLocalErreur);
	    $result = httpQuery($api_url_request.$path, $method, $data, NULL, $header, true, true, &$info, null);
		if (strpos($result, 'Connection timed out') !== false)
		{	
			$result =  '{"error":"Connection timed out"}';
		}
		else if (strpos($result, 'Failed to connect') !== false)
		{
			$result =  '{"error":"Failed to connect"}';
		}
	}
	else
	{
	   $result =  '{"error":"countProtect"}';
	}
	//if ($modeLocalToken) {echo  $result . '<br/><br/>';}
	$debug = loadVariable('debug');
	if ($debug == 'on')
	{
		$debugDisplay = loadVariable('debugDisplay');
		$debugCount = loadVariable('debugCount');
		$debugDisplay[$debugCount] = sdk_json_decode($result);
		$debugCount++;
		saveVariable('debugDisplay', $debugDisplay);
		saveVariable('debugCount', $debugCount);
	}
	$modeLocalErreur[] = 'httpQuery : ' . $result;
	saveVariable('modeLocalErreur', $modeLocalErreur);
	if ($path == 'setup') {saveVariable('modeLocalErreur2', $result);}
	return sdk_json_decode($result);
}

// Login et abonnement aux événements somfy
function sdk_login($eeDevices)
{
	global $modeLocal;
	global $modeLocalToken;
	global $modeLocalTest;
	global $adresseMAC;
	global $forceModeCloud;
	global $modeLocalErreur;
	
	$data = array(
		'userId' => loadVariable('username'),
		'userPassword' => loadVariable('password'),
	);
	
	$countProtect = loadVariable('countProtect');	// Protection contre les trop nombreux logins
		
	// On essaye de se connecter en local, à chaque étape, si KO, on repasse en cloud
	if (!$forceModeCloud && ($adresseMAC <> ''))
	{
		$modeLocal = 1;
		$modeLocalToken = 0;
		if ($countProtect['count'] < 3)
		{
			// login
			$answerLogin = sdk_make_request('login', 'POST', $data, 'x-www-form-urlencoded');
			$answerLoginTemp = $answerLogin;
		}
		else
		{
			$answerLogin = array(
					'error' => 'countProtect',
			   );
		}
		
		if (($answerLogin['success'] == 'true') && ($answerLogin['roles'][0]['name'] == 'ENDUSER'))
		{
			$countProtect = sdk_countProtect('reset');
			
			// Demande de token
			$answerToken = sdk_make_request('config/'.$eeDevices['MasterDataSomfy']['MasterDataSomfyURL'].'/local/tokens/generate', 'GET', NULL, 'json');
			if (array_key_exists('token',$answerToken))
			{
				// Activation du token
				$json = '{"label": "tokenEedomus","token": "' . $answerToken['token'] . '","scope": "devmode"}';
				$answerActivate = sdk_make_request('config/'.$eeDevices['MasterDataSomfy']['MasterDataSomfyURL'].'/local/tokens', 'POST', $json, 'json');
				if (array_key_exists('requestId',$answerActivate))
				{
					$modeLocalToken = 1;
					$localToken = $answerToken['token'];
					saveVariable('localToken', $localToken);
					// Abonnement aux événements en local
					$answerRegister = sdk_make_request('events/register', 'POST', NULL, NULL, $localToken);
					if (array_key_exists('id',$answerRegister))
					{
						$localRegisterId = $answerRegister['id'];
						saveVariable('localRegisterId', $localRegisterId);
						$modeLocalErreur[] = 'Login : OK abonnement';
						saveVariable('modeLocalErreur', $modeLocalErreur);
					}
					else
					{
						$modeLocal = 0;
						$modeLocalToken = 0;
						$modeLocalErreur[] = 'Login : PB abonnement';
						saveVariable('modeLocalErreur', $modeLocalErreur);
					}
				}
				else
				{
					$modeLocal = 0;
					$modeLocalErreur[] = 'Login : PB activation';
					saveVariable('modeLocalErreur', $modeLocalErreur);
				}
			}
			else
			{
				$modeLocal = 0;
				$modeLocalErreur[] = 'Login : PB token';
				saveVariable('modeLocalErreur', $modeLocalErreur);
			}
		}
		else
		{
			$modeLocal = 0;
			$modeLocalErreur[] = 'Login : PB login ' . $answerLogin['error'];
			saveVariable('modeLocalErreur', $modeLocalErreur);
		}
	}
	else
	{
		$modeLocal = 0;
		$modeLocalToken = 0;
		$modeLocalErreur[] = 'Login : Adresse MAC non renseignée ou mode forceCloud';
		saveVariable('modeLocalErreur', $modeLocalErreur);
	}
	saveVariable('modeLocal', $modeLocal);
	saveVariable('modeLocalToken', $modeLocalToken);
	
	if (!$modeLocal)
	{	// On est repassé en mode cloud

		if ($countProtect['count'] < 3)
		{
			$answerLogin = sdk_make_request('login', 'POST', $data);
			$answerLoginTemp = $answerLogin;
		}
		else
		{
			$answerLogin = array(
					'error' => 'countProtect',
			   );
		}

		if (($answerLogin['success'] == 'true') && ($answerLogin['roles'][0]['name'] == 'ENDUSER'))
		{
			$countProtect = sdk_countProtect('reset');
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
			if ($countProtect['count'] < 3)
			{	// si on n'a pas encore 3 tentatives en echec
				$countProtect['count'] = $countProtect['count'] + 1;
				$countProtect['display'][$countProtect['count']] = $answerLoginTemp;
				if ($countProtect['count'] == 3)
				{	// on bloque
					if ($countProtect['startTime'] == 0)
					{	// premier blocage
						$countProtect['startTime'] = time();
						$countProtect['offset'] = 300;
					}
					else
					{	// on double le timer
						$calcOffset = $countProtect['offset'];
						if ($calcOffset < 14400)
						{	// on ne bloque pas plus de 4 heures
							$countProtect['offset'] = $calcOffset * 2;
						}
					}
				}
			}
		}
	}

	saveVariable('countProtect', $countProtect);

	return $resultLogin;
}

// Récupère les gateways, les pièces, les scenarios et les périphériques
function sdk_get_setup($eeDevices, $token=NULL)
{
	global $modeLocalToken;
	global $modeLocalErreur;

	// On récupère toutes les informations gateways, devices, zones, disconnectionConfiguration, rootPlace et features
	$setup = sdk_make_request('setup', 'GET', NULL, NULL, $token);

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
			if ($modeLocalToken)
			{
				$gateways[$gateway_id]['mode'] = ($gateway['connectivity']['status'] == 'OK') ? 'ACTIVE' : 'OFF';
			}
			else
			{
				$gateways[$gateway_id]['mode'] = $gateway['mode'];
			}
			
			if (($eeDevices != '') && ($gateway_id == $eeDevices['MasterDataSomfy']['MasterDataSomfyURL']))
			{	// c'est la gateway principale
				$statutGatewayPrincpale = ($modeLocalToken) ? true : $gateway['alive'];
				$gateways[$gateway_id]['type'] = ($modeLocalToken) ? 29 : $gateway['type'];
				$gateways[$gateway_id]['mode'] = ($modeLocalToken) ? "ACTIVE" : $gateway['mode'];
			}
		}
	}
 
	// Les devices
	$devices = array();
	foreach ($setup['devices'] as $device)
	{
		$device_url = str_replace('\\', '', $device['deviceURL']);
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
	
	
	// En mode cloud, on récupère les scenarios
	if (!$modeLocalToken)
	{
		$actionGroups = sdk_make_request('actionGroups', 'GET', NULL, NULL, $token);
		$scenarios = array();
		foreach ($actionGroups as $actionGroup)
		{
			$scn_oid = $actionGroup['oid'];
			$scenarios[$scn_oid]['oid'] = $actionGroup['oid'];
			$scenarios[$scn_oid]['label'] = $actionGroup['label'];
		}
	}
	else
	{
		$scenarios = NULL;
	}
	
	$modeLocalErreur[] = 'Setup : avant return';
	saveVariable('modeLocalErreur', $modeLocalErreur);
	
	return array(
		'devices' => $devices,
		'alive' => $statutGatewayPrincpale,
		'gateways' => $gateways,
		'scenarios' => $scenarios,
	);
}

// Récupère l'état d'un Gateway
function sdk_getGatewayStatus($deviceUrl, $token=NULL)
{   
	global $modeLocalToken;
	global $modeLocalErreur;
	
	if ($modeLocalToken)
	{
		$commande = 'setup/gateways';
		$gatewaysDesc = sdk_make_request($commande, 'GET', NULL, NULL, $token);
		foreach ($gatewaysDesc as $gatewayDesc)
		{
			if ($gatewayDesc['gatewayId'] == $deviceUrl)
			{
				$deviceStates['mode'] = ($gatewayDesc['connectivity']['status'] == 'OK') ? 'ACTIVE' : 'OFF';
			}
		}
	}
	else
	{
		$commande = 'setup/gateways/' . urlencode($deviceUrl);
		$deviceStates = sdk_make_request($commande, 'GET');
	}
	return $deviceStates;
}

// Récupère l'état d'un périphérique
function sdk_getDeviceStatus($deviceUrl, $token=NULL)
{   
	global $modeLocalErreur;
	
	$commande = 'setup/devices/' . urlencode($deviceUrl) . '/states';
	$deviceStates = sdk_make_request($commande, 'GET', NULL, NULL, $token);
	//if ($modeLocalToken) {echo 'commande : ' . $commande . '<br/>';}
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
	global $modeLocal;
	global $modeLocalToken;
	global $modeLocalErreur;
	
	$eeResultat = ($modeLocal) ? 5 : 3;
	$horsPortee = 0;
	$modeLocalErreur[] = 'Process : init eeResultat : ' . $eeResultat;
	saveVariable('modeLocalErreur', $modeLocalErreur);
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
	
	// Traitement des Gateways
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
		$eeResultat = ($modeLocal) ? 4 : 2;
	}
	saveVariable('horsPortee', $horsPortee);
	$modeLocalErreur[] = 'Process : fin eeResultat : ' . $eeResultat;
	saveVariable('modeLocalErreur', $modeLocalErreur);
	return $eeResultat;
}

// On applique une commande aux périphériques d'une pièce
function sdk_apply_command($device_urls, $commands, $path='exec/apply', $token=NULL)
{
	global $modeLocalToken;
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
	$additionalParam = ($modeLocalToken) ? '' : '"notificationTypeMask":"0","notificationCondition":"NEVER",';
	$json = '{"label":"eedomus command",'.$additionalParam.'"actions":['.implode($actions, ',').']}';
	//$json = '{"label":"eedomus command","notificationTypeMask":"0","notificationCondition":"NEVER","actions":['.implode($actions, ',').']}';

	return sdk_make_request($path, 'POST', $json, 'json', $token);
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
function sdk_display_equipements($devices,$gateways,$scenarios,$migration=false)
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
	
	if (!empty($scenarios))
	{
		echo '<h1>'.'D) Liste des scenarios'.' :</h1>';
		$iScn = 1;
		foreach ($scenarios as $scenario)
		{
			echo '<p><b>' . $iScn . ') ' . $scenario['label'].'</b></br>Adresse du scenario [VAR1] : <input onclick="this.select();" type="text" size="40" readonly="readonly" value="'.urlencode($scenario['oid']).'"></p>';
			$iScn++;
		}
	}
	
	echo '</body>';
	return;
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
		$modeLocalErreur[] = 'Config : début';
		// Force le mode cloud lors de l'affichage de la liste
		if ($modeLocal)
		{
			$forceModeCloud = 1;
			$modeLocal = 0;
			$modeLocalTmp = 1;
		}
		else
		{
			$modeLocalTmp = 0;
		}
		
		// Traitement des actions POST
		if (isset($_POST['submit']))
		{
			$modeLocalErreur[] = 'Config : dans les actions post';
			$countProtect = sdk_countProtect('reset');
			saveVariable('countProtect', $countProtect);
			saveVariable('debug', 'off');
			saveVariable('username', $_POST['username']);
			saveVariable('password', $_POST['password']);
			$MasterDataSomfy['valeur'] = '';
			saveVariable('MasterDataSomfy', $MasterDataSomfy);
		}
		$modeLocalErreur[] = 'Config : avant 1er fetch';
		$resultFetch =  sdk_make_request('events/' . $registerId . '/fetch', 'POST');
		if ($forceModeCloud)
		{	// on force le login en mode cloud
			$resultFetch['error'] = 'forceModeCloud';
			$modeLocalErreur[] = 'Config : dans result fetch force error';
		}
		if (array_key_exists('error',$resultFetch))
		{	$modeLocalErreur[] = 'Config : result fetch error';
			$testUser = loadVariable('username');
			if ($testUser == '')
			{
				sdk_display_login_form('Veuillez renseigner vos identifiants Somfy.');
			}
			else
			{
				if (sdk_login($eeDevices) == 'ERROR_LOGIN')
				{	// Erreur d'identification au cloud
					sdk_display_login_form('', 'Identifiants de connexion incorrects');
				}
			}
		}
		$modeLocalErreur[] = 'Config : avant setup';
		$setup = sdk_get_setup($eeDevices);
		$modeLocalErreur[] = 'Config : après setup';
		if (!count($setup['devices']))
		{
			$modeLocalErreur[] = 'Config : setup failed, affiche login';
			sdk_display_login_form('', 'Aucun périphérique détecté.');
		}
		$modeLocalErreur[] = 'Config : avant display';
		sdk_display_equipements($setup['devices'],$setup['gateways'],$setup['scenarios']);
		$modeLocalErreur[] = 'Config : après display';
		saveVariable('modeLocalErreur', $modeLocalErreur);
		saveVariable('modeLocal', $modeLocalTmp);		// retour au mode initial
		saveVariable('modeLocalToken', $modeLocalTmp);		// retour au mode initial
		break;
	case 'reset' :
		//------------------------------------
		// Remise à zéro des données
		//------------------------------------
		$eeDevices = '';
		saveVariable('eeDevices', $eeDevices);
		$MasterDataSomfy['valeur'] = '';
		saveVariable('MasterDataSomfy', $MasterDataSomfy);
		$countProtect = sdk_countProtect('reset');
		saveVariable('countProtect', $countProtect);
		saveVariable('debug', 'off');
		echo 'Reset effectué';
		break;
	case 'resetCount' :
		$countProtect = sdk_countProtect('reset');
		saveVariable('countProtect', $countProtect);
		$MasterDataSomfy['valeur'] = '';
		saveVariable('MasterDataSomfy', $MasterDataSomfy);
		break;
	case 'pause' :
		$countProtect = sdk_countProtect('pause');
	    saveVariable('countProtect', $countProtect);
		$MasterDataSomfy['valeur'] = 10;
		saveVariable('MasterDataSomfy', $MasterDataSomfy);
		break;
	case 'debugON' : 
	    saveVariable('debug', 'on');
		saveVariable('debugDisplay', array());
		saveVariable('debugCount', 0);
		echo 'mode debug ON';
	    break;
	case 'debugOFF' : 
	    saveVariable('debug', 'off');
		saveVariable('debugDisplay', array());
		saveVariable('debugCount', 0);
		echo 'mode debug OFF';
	    break;
	case 'resetUser' : 
	    saveVariable('username', '');
		saveVariable('password', '');
	    break;
	case 'display' :
		//------------------------------------
		// Utilisé pour les tests
		//------------------------------------
		//$tesID = getArg('eedomus_controller_module_id');
		//$priphValeur = getPeriphList(true, $tesID);
		$countProtect = loadVariable('countProtect');
		$debug = loadVariable('debug');
		if ($debug == 'on')
		{
			$debugDisplay = loadVariable('debugDisplay');
			echo '<br/>Debug reponses API : <pre>';  var_dump($debugDisplay) ; echo '</pre>';
		}
		echo '<br/>Count Protect = ' . $countProtect['count'] . '<br/>';
		if ($countProtect['count'] == 3)
		{
			echo 'depuis = ' . date('Y-m-d H:i:s', $countProtect['startTime']) . '<br/>';
			$tempsRestant =  $countProtect['startTime'] + $countProtect['offset'] - time();
			echo 'nouvel essai dans = ' . $tempsRestant . ' secondes<br/>';
		}
		echo 'modeLocal = ' . $modeLocal . '<br/>';
		echo 'modeLocalToken = ' . $modeLocalToken . '<br/>';
		echo 'adresseMAC = ' . $adresseMAC . '<br/>';
		echo 'modeLocalTest = ' . $modeLocalTest . '<br/>';
		echo 'forceModeCloud = ' . $forceModeCloud . '<br/>';
		$timerLocal = loadVariable('timerLocal');
		echo 'timerLocal = ' . $timerLocal . '<br/>';
		$modeLocalErreur = loadVariable('modeLocalErreur');
		echo '<br/>ModeLocalErreur : <pre>';  var_dump($modeLocalErreur) ; echo '</pre>';
		echo 'localToken = ' . $localToken . '<br/>';
		$modeLocalErreur2 = loadVariable('modeLocalErreur2');
		echo '<br/>Setup : <pre>';  var_dump($modeLocalErreur2) ; echo '</pre>';
		//echo '<br/>Affichage erreurs login : <pre>';  var_dump($countProtect['display']) ; echo '</pre>';
		//echo '<br/>Affichage des périphériques initialisés : <pre>';  var_dump($eeDevices) ; echo '</pre>';
		//$setup = sdk_get_setup($eeDevices);
		//sdk_display_equipements($setup['devices'],$setup['gateways'],$setup['scenarios']);
		
		break;
	case 'migration' :
		//------------------------------------
		// Utilisé pour la migration en V3
		//------------------------------------
		
		// Force le mode cloud lors de l'affichage de la liste
		if ($modeLocal)
		{
			$forceModeCloud = 1;
			$modeLocal = 0;
			$modeLocalTmp = 1;
		}
		else
		{
			$modeLocalTmp = 0;
		}
		
		// Traitement des actions POST
		if (isset($_POST['submit']))
		{
			$countProtect = sdk_countProtect('reset');
			saveVariable('countProtect', $countProtect);
			saveVariable('countProtectDisplay', array());
			saveVariable('username', $_POST['username']);
			saveVariable('password', $_POST['password']);
		}
		
		$resultFetch =  sdk_make_request('events/' . $registerId . '/fetch', 'POST');
		if ($forceModeCloud)
		{	// on force le login en mode cloud
			$resultFetch['error'] = 'forceModeCloud';
		}
		if (array_key_exists('error',$resultFetch))
		{
			$testUser = loadVariable('username');
			if ($testUser == '')
			{
				sdk_display_login_form('Veuillez renseigner vos identifiants Somfy.');
			}
			else
			{
				if (sdk_login($eeDevices) == 'ERROR_LOGIN')
				{	// Erreur d'identification au cloud
					sdk_display_login_form('', 'Identifiants de connexion incorrects');
				}
			}
		}
		echo '<h1>Affichage détaillé de tous les équipements : </h1>';
		$setup = sdk_get_setup($eeDevices);
		sdk_display_equipements($setup['devices'],$setup['gateways'],$setup['scenarios'],true);
		saveVariable('modeLocal', $modeLocalTmp);		// retour au mode initial
		saveVariable('modeLocalToken', $modeLocalTmp);		// retour au mode initial
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
		//---------------------------------------------------------------
		// Compatibilité retour d'état version 1 =>>>> fonction déclassée
		//---------------------------------------------------------------
		if ($modeLocal)
		{
			$forceModeCloud = true;
			$modeLocal = 0;
			$modeLocalTmp = 1;
		}
		else
		{
			$modeLocalTmp = 0;
		}
		$resultRefresh =  sdk_make_request('setup/devices/states/refresh', 'POST');
		if ($forceModeCloud)
		{	// on force le login en mode cloud
			$resultFetch['error'] = 'forceModeCloud';
		}
		if (array_key_exists('error',$resultRefresh))
		{
			if (sdk_login($eeDevices) != 'ERROR_LOGIN')
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
		saveVariable('modeLocal', $modeLocalTmp);		// retour au mode initial
		break;
	case 'getAllStates' :
		//--------------------------------------------------
		// MasterData SOMFY et retour d'états version 2 et +
		//--------------------------------------------------
		
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
		
		// enregistre l'adresse MAC du MasterData
		$adresseMAC = getArg('adresseMAC', false);
		saveVariable('adresseMAC', $adresseMAC);
		if ($adresseMAC == '')
		{	
			$modeLocal = 0;
			saveVariable('modeLocal', 0);
			$modeLocalToken = 0;
			saveVariable('modeLocalToken', 0);
		}
        
		$countProtect = loadVariable('countProtect');	// Protection contre les trop nombreux logins
		if (!array_key_exists('count',$countProtect))
		{
			$countProtect = sdk_countProtect('reset');
			saveVariable('countProtect', $countProtect);
		}
			
        if (($MasterDataSomfy['valeur'] <> 10) && (sdk_countProtect('OK', $countProtect)))
		{	// On n'est pas en mode pause (10) ni en mode vérouillé (9)
			// Lecture des événements
			$modeLocalErreur[] = 'Init : avant 1er fetch';
			saveVariable('modeLocalErreur', $modeLocalErreur);
			$regId = ($modeLocalToken) ? $localRegisterId : $registerId;
			if (sdk_checkModeLocal())
			{	// on continue avec le fetch
				$resultFetch =  sdk_make_request('events/' . $regId . '/fetch', 'POST', NULL, NULL, $localToken);
			}
			else
			{	// on force l'erreur pour retester en mode local
				$resultFetch =  array('error' => 'teste mode local');
				$modeLocalErreur[] = 'Erreur 1er fetch => retente mode local';
			}
			if (array_key_exists('error',$resultFetch))
			{	$modeLocalErreur[] = 'Erreur 1er fetch => login';
				saveVariable('modeLocalErreur', $modeLocalErreur);
				if (sdk_login($eeDevices) == 'ERROR_LOGIN')
				{	// Erreur d'identification au cloud
					$countProtect = loadVariable('countProtect');	// Protection contre les trop nombreux logins
					$eeResultat = ($countProtect['count'] >= 3) ? 9 : 0;
				}
				else
				{
					$setup = sdk_get_setup($eeDevices, $localToken);
					if ($setup['alive'] <> 'true')
					{	// La box n'est pas connectée à son cloud
						$eeResultat = 1;
					}
					else
					{
						$eeResultat = sdk_process_setup($setup,$eeDevices);
					}
					$regId = ($modeLocalToken) ? $localRegisterId : $registerId;
					$resultFetch =  sdk_make_request('events/' . $regId . '/fetch', 'POST', NULL, NULL, $localToken);
				}
			}
			else
			{	// le fetch s'est bien passé, on reprend la valeur sauvegardée du MasterData somfy ou on la recalcule
				$modeLocalErreur[] = 'Init : 1er fetch bien passé';
				saveVariable('modeLocalErreur', $modeLocalErreur);
				if (($MasterDataSomfy['valeur'] <> '') && ($MasterDataSomfy['valeur'] <> 0))
				{
					if ($MasterDataSomfy['valeur'] == 9)
					{
						$eeResultat = ($modeLocal) ? 5 : 3;
					}
					else
					{
						if  (($MasterDataSomfy['valeur'] == 2) or ($MasterDataSomfy['valeur'] == 4))
						{
							$eeResultat = ($modeLocal) ? 4 : 2;
						}
						else if (($MasterDataSomfy['valeur'] == 3) or ($MasterDataSomfy['valeur'] == 5))
						{
							$eeResultat = ($modeLocal) ? 5 : 3;
						}
						else if ($MasterDataSomfy['valeur'] == 1)
						{
							if ($modeLocal)
							{
								$setup = sdk_get_setup($eeDevices, $localToken);
								if ($setup['alive'] <> 'true')
								{	// La box n'est pas connectée à son cloud
									$eeResultat = 1;
								}
								else
								{
									$eeResultat = sdk_process_setup($setup,$eeDevices);
								}
							}
							else
							{
								$eeResultat = $MasterDataSomfy['valeur'];
							}
						}
						else
						{
							$eeResultat = $MasterDataSomfy['valeur'];
						}
					}
				}
				else
				{
					$setup = sdk_get_setup($eeDevices, $localToken);
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
		}
		else
		{
			$eeResultat = $MasterDataSomfy['valeur'];
		}

		if (($eeResultat > 0) && ($eeResultat < 9))
		{
			// On est connecté au cloud ou en local et on peut traiter les événements
			foreach ($resultFetch as $evenement)
			{
				$modeLocalErreur[] = $evenement ;
				saveVariable('modeLocalErreur', $modeLocalErreur);
				switch ($evenement['name'])
				{
					case 'GatewayAliveEvent' :
						if ($evenement['gatewayId'] == $eeDevices['MasterDataSomfy']['MasterDataSomfyURL'])
						{
							$setup = sdk_get_setup($eeDevices, $localToken);
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
						$deviceUrl = str_replace('\\', '', $evenement['deviceURL']);
						if (array_key_exists($deviceUrl,$eeDevices))
						{
							foreach ($evenement['deviceStates'] as $deviceState)
							{
								if (array_key_exists($deviceState['name'],$eeDevices[$deviceUrl]))
								{
									sdk_maj_periph($eeDevices,$deviceUrl,$deviceState['name'],$deviceState['value']);
								}
								
								// Prise en compte de l'état en mode local
								if ($deviceState['name'] == 'core:StatusState')
								{
									if ($deviceState['value'] == 'available')
									{
										$horsPortee = loadVariable('horsPortee');
										$horsPortee = ($horsPortee == 0) ? 0 : --$horsPortee;
										saveVariable('horsPortee', $horsPortee);
										if ($horsPortee)
										{
											$eeResultat = ($modeLocal) ? 4 : 2;
										}
										else
										{
											$eeResultat = ($modeLocal) ? 3 : 5;
										}
										$deviceUrl = str_replace('\\', '', $evenement['deviceURL']);
										$deviceStates = sdk_getDeviceStatus($deviceUrl, $token);
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
									}
									if ($deviceState['value'] == 'unavailable')
									{
										$horsPortee = loadVariable('horsPortee');
										$horsPortee++;
										saveVariable('horsPortee', $horsPortee);
										$eeResultat = ($modeLocal) ? 4 : 2;
										$deviceUrl = str_replace('\\', '', $evenement['deviceURL']);
										if (array_key_exists($deviceUrl,$eeDevices))
										{
											sdk_maj_periph($eeDevices,$deviceUrl,'all','Connexion');
										}
									}
								}
							}
						}
						break;
					case 'ExecutionStateChangedEvent' :		// la commande envoyée revient en erreur
						if ($evenement['newState'] == 'FAILED')
						{
							// recherche de la cause
							$path = 'history/executions/' . $evenement['execId'];
							$resultHistory = sdk_make_request($path, $method='GET', NULL, NULL, $token);
							foreach ($resultHistory['execution']['commands'] as $execCommand)
							{
								if ($execCommand['state'] == 'FAILED')
								{
									$deviceUrl = str_replace('\\', '', $execCommand['deviceURL']);
									if (in_array($execCommand['failureType'], array('NONEXEC_OTHER')))
									{
										$deviceStates = sdk_getDeviceStatus($deviceUrl, $token);
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
										
									}
									else
									{
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
						else if ($evenement['newState'] == 'COMPLETED')
						{
							unset($execIds[$evenement['execId']]);
							saveVariable('execIds', $execIds);
						}
						break;
					case 'DeviceUnavailableEvent' :
						$horsPortee = loadVariable('horsPortee');
						$horsPortee++;
						saveVariable('horsPortee', $horsPortee);
						$eeResultat = 2;
						$deviceUrl = str_replace('\\', '', $evenement['deviceURL']);
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
						$deviceUrl = str_replace('\\', '', $evenement['deviceURL']);
						$deviceStates = sdk_getDeviceStatus($deviceUrl, $token);
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
		else
		{
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
		$MasterDataSomfy['valeur'] = $eeResultat;
		saveVariable('MasterDataSomfy', $MasterDataSomfy);
		
		$xml = '<?xml version="1.0" encoding="ISO-8859-1"?><somfy><resultat>'.$eeResultat.'</resultat></somfy>';
		echo $xml;
		break;
	case 'auto' :
		//------------------------------------------------------------------------
		// remise en statut auto des actionneurs http multiples et actionneurs RTS
		//------------------------------------------------------------------------
		$deviceEtat = getArg('etat', false);						// Etat à traiter pour le retour d'état																										
		if ($deviceEtat == 'mode:CommandState')
		{
			// @dommarion [VAR2] = mode:CommandState signifie pas de retour d'état, on garde la dernière commande comme retour d'état
			$device_urls = explode(',', getArg('devices', false));		// les périphériques à traiter
			$xml = '<?xml version="1.0" encoding="ISO-8859-1"?><somfy><resultat>'.$eeDevices[$device_urls[0]]["mode:CommandState"]["eeDeviceCommandName"].$eeDevices[$device_urls[0]]["mode:CommandState"]["eeDeviceValues"].'</resultat><Timestamp>'.time().'</Timestamp></somfy>';
		}
		else
		{
			$xml = '<?xml version="1.0" encoding="ISO-8859-1"?><somfy><resultat>unknow</resultat><Timestamp>'.time().'</Timestamp></somfy>';
		}
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
														'eeDeviceValues' => $value,
														);
			saveVariable('eeDevices', $eeDevices);
			if ($deviceEtat<> 'mode:GatewayState')
			{
				$deviceStates = sdk_getDeviceStatus($device_urls[0], $token);
				if (array_key_exists('error',$deviceStates))
				{
					if (sdk_login($eeDevices) != 'ERROR_LOGIN')
					{
						$deviceStates = sdk_getDeviceStatus($device_urls[0], $token);
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
				$deviceStates = sdk_getGatewayStatus($device_urls[0], $localToken);
				if (array_key_exists('error',$deviceStates))
				{
					if (sdk_login($eeDevices) != 'ERROR_LOGIN')
					{
						$deviceStates = sdk_getGatewayStatus($device_urls[0], $localToken);
					}
				}
				$xml = '<?xml version="1.0" encoding="ISO-8859-1"?><somfy><gateway>' . $deviceStates['mode'] . '</gateway></somfy>';
				echo $xml;
			}
		}
		break;
	case 'scenario' :
		// scenarios non supportés en mode local, on force le login en mode cloud
		$resultLoginScenario = false;
		if ($modeLocal)
		{
			$forceModeCloud = true;
			$modeLocal = 0;
			$modeLocalTmp = 1;
			if (sdk_login($eeDevices) != 'ERROR_LOGIN')
			{
				$resultLoginScenario = true;
			}
		}
		else
		{
			$resultLoginScenario = true;
			$modeLocalTmp = 0;
		}
		
		if ($resultLoginScenario)
		{
			$device_urls = explode(',', getArg('devices', false));		// le scenario a traiter
			if (count($device_urls) == 1)
			{
				$commands[$action] = null;
				$path='exec/' . $device_urls[0];
				// Exécution de la commande
				$resultCommand = sdk_apply_command($device_urls, $commands, $path);
				if (array_key_exists('error',$resultCommand))
				{
					if (sdk_login($eeDevices) != 'ERROR_LOGIN')
					{
						$resultCommand = sdk_apply_command($device_urls, $commands, $path);
					}
				}
			}
		}
		saveVariable('modeLocal', $modeLocalTmp);		// retour au mode initial
		saveVariable('modeLocalToken', $modeLocalTmp);		// retour au mode initial
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
														'eeDeviceValues' => $value,
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
		$resultCommand = sdk_apply_command($device_urls, $commands, $path, $localToken);
		if (array_key_exists('error',$resultCommand))
		{
			if (sdk_login($eeDevices) != 'ERROR_LOGIN')
			{
				$resultCommand = sdk_apply_command($device_urls, $commands, $path);
			}
		}
		if (array_key_exists('execId',$resultCommand))
		{
			$execIds[$resultCommand['execId']] = $device_urls;
			saveVariable('execIds', $execIds);
		}
}

?>
