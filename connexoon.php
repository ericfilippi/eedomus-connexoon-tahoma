<?php
// script créé par Patrice Gauchon pour eedomus
// Version 1.2.2 / 27 juin 2020

$api_url = 'https://www.tahomalink.com/enduser-mobile-web/enduserAPI/';
$cache_time = 240; // 4 minutes

$devicesControllableNames = array(
	'io:RollerShutterVeluxIOComponent' => 'volet IO',
	'io:RollerShutterGenericIOComponent' => 'volet IO',
	'io:RollerShutterWithLowSpeedManagementIOComponent' => 'volet IO',
	'io:WindowOpenerVeluxIOComponent' => 'volet IO',
	'io:DiscreteGarageOpenerIOComponent' => 'porte ou portail',
	'rts:SwingingShutterRTSComponent' => 'volet RTS',
	'io:ExteriorVenetianBlindIOComponent' => 'BSO IO',
);

$action = getArg('action', false);
$cache = loadVariable('cache');

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

// Login
function sdk_login()
{
	global $api_url;

	$data = array(
		'userId' => loadVariable('username'),
		'userPassword' => loadVariable('password'),
	);

	return sdk_make_request('login', 'POST', $data);
}

// Récupère les pièces et les périphériques
function sdk_get_setup($ignore_cache=false)
{
	global $cache, $cache_time, $devicesControllableNames;

	if (getArg('ignoreCache', false) == 'true')
	{
		$ignore_cache = true;
	}

	// retourne la sauvegarde en cache si dernière requête < $cache_time
	if (!$ignore_cache and isset($cache['time']) and time() - $cache['time'] < $cache_time and !empty($cache['devices']))
	{
		return array(
			'devices' => $cache['devices'],
			'time' => $cache['time'],
		);
	}

	// Login
	sdk_login();

	// On rafraichit les états
	sdk_make_request('setup/devices/states/refresh', 'POST');

	// On récupère les périphériques
	$setup = sdk_make_request('setup', 'GET');
	$devices = array();
	$other_devices = array();
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

	// Sauvegarde des données en cache
	$time = time();
	$cache['devices'] = $devices;
	$cache['time'] = $time;
	saveVariable('cache', $cache);

	return array(
		'devices' => $devices,
		'time' => $time,
	);
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

	$setup = sdk_get_setup(true);

	if (!count($setup['devices']))
	{
		sdk_display_login_form('', 'Aucun périphérique détecté.');
	}

	sdk_display_devices($setup['devices']);
}

//------------------------------
// Actions
//------------------------------

// Set Closure
if ($action == 'setClosure')
{
	sdk_login();
	$value = getArg('value');
	$device_urls = explode(',', getArg('devices'));

	$commands['setClosure'] = $value;

	// On invalide le cache
	$cache['time'] = 0;
	saveVariable('cache', $cache);

	sdk_apply_command($device_urls, $commands);
}

// Set SlateOrientation
if ($action == 'setSlateOrientation')
{   
   sdk_login();
   $value = getArg('value');
   $device_urls = explode(',', getArg('devices'));
   
   $commands['setOrientation'] = $value;
   
   // On invalide le cache
   $cache['time'] = 0;
   saveVariable('cache', $cache);

   sdk_apply_command($device_urls, $commands);
   
}

// Portail
if (in_array($action, array('close','open','up','down','stop')))
{
	sdk_login();
	$device_urls = explode(',', getArg('devices'));

	$commands[$action] = null;

	// On invalide le cache
	$cache['time'] = 0;
	saveVariable('cache', $cache);

	$result = sdk_apply_command($device_urls, $commands, 'exec/apply/highPriority');
}

//------------------------------
// Polling (XML)
//------------------------------

if ($action == 'getState')
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

?>