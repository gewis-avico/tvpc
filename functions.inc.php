<?php
function loadSettings(){
	global $_GET;
	global $_REQUEST;
	
	// Load default settings
	$settings = json_decode(file_get_contents('./settings.txt'), true);
	// Load settings from vvz
	$json = file_get_contents('https://gewis.nl/~vvz/tvpc/settings.txt');
	$altsettings = json_decode($json, true);
	$settings = @array_merge($settings, $altsettings);
	// Load BAC-settings during borrel

	$borrel_id = file_get_contents('https://secure.gewis.nl/susos/getcurrentborrelid.php');
	if ($borrel_id != '-1' && !isset($_GET['noborrel']))
	{	
		$altsettings_url = 'https://gewis.nl/~bac/tvpc/settings.txt';	
	}
	// Uncomment the assignment below to add in alternative settings
	//$altsettings_url = 'https://gewis.nl/<. commissie .>/tvpc/settings.txt';
	// Load alternative settings if defined
	if (isset($altsettings_url))
	{
		$json = file_get_contents($altsettings_url);
		$altsettings = json_decode($json, true);
		$settings = @array_merge($settings, $altsettings);
	}

	
	return $settings;
}
?>