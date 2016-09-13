<?php

error_reporting(0);

// Variables
require_once(__DIR__ . DIRECTORY_SEPARATOR . "config.php");

// Start setup
header("Content-Type:text/html; charset=UTF-8");
chdir(__DIR__);
$path = dirname($_SERVER["SCRIPT_NAME"]);

// Get default settings
$settings = json_decode(file_get_contents($loc_default_settings));

try {
	// Get VVZ settings
	$settings_vvz = json_decode(file_get_contents($loc_vvz_settings));
	if (!empty($settings_vvz->version) && $settings_vvz->version == "2") {
		// Current version
		@array_merge($settings, $settings_vvz);
	} else {
		// Backwards compatibility, version 1 -> current
		if (!empty($settings_vvz->timeout)) {
			$settings->timeout = $settings_vvz->timeout;
		}
		if (!empty($settings_vvz->pages)) {
			$settings->pages = array();
			foreach ($settings_vvz->pages as $entry) {
				switch ($entry) {
					case "logo":
					case "agenda":
						$settings->pages[] = (object) array(
								"type" => $entry,
								"timeout" => (!empty($settings_vvz->timeout_special->{$entry}) ? $settings_vvz->timeout_special->{$entry} : $settings->timeout)
							);
						break;
					case "randominfima":
						$settings->pages[] = (object) array(
								"type" => "infima",
								"timeout" => (!empty($settings_vvz->timeout_special->randominfima) ? $settings_vvz->timeout_special->randominfima : $settings->timeout)
							);
						break;
					case "posters":
						$settings->pages[] = (object) array(
								"type" => "poster",
								"timeout" => (!empty($settings_vvz->timeout_special->{$entry}) ? $settings_vvz->timeout_special->{$entry} : $settings->timeout),
								"posters" => $settings_vvz->posters
							);
						break;
					case "randomposter":
						$settings->pages[] = (object) array(
								"type" => "randomposter",
								"timeout" => (!empty($settings_vvz->timeout_special->randomposters) ? $settings_vvz->timeout_special->randomposters : $settings->timeout),
								"posters" => $settings_vvz->randomposters
							);
						break;
					case "photo":
						$settings->pages[] = (object) array(
								"type" => "photo",
								"timeout" => (!empty($settings_vvz->timeout_special->photo) ? $settings_vvz->timeout_special->photo : $settings->timeout),
								"galleries" => $settings_vvz->galleries
							);
						break;
					default:
						if (preg_match("/^https?:\/\//", strtolower($entry))) {
							$settings->pages[] = (object) array(
									"type" => "external",
									"timeout" => (!empty($settings_vvz->timeout_special->{$entry}) ? $settings_vvz->timeout_special->{$entry} : $settings->timeout),
									"source" => $entry
								);
						} else {
							// Ignore, type deprecated or unknown
						}
						break;
				}
			}
			// Add new pages
			$settings->pages[] = (object) array(
					"type" => "ns",
					"timeout" => 15
				);
		}
	}
} catch(Exception $e) {
}

try {
		// Get BAC settings
		if (array_key_exists("borrel", $_GET) || file_get_contents($loc_is_borrel) != "-1") {
			$settings_bac = json_decode(file_get_contents($loc_bac_settings), true);
			$settings->skin = "bac";
			if (!empty($settings_bac->version) && $settings_bac->version == "2") {
				// Current version
				@array_merge($settings, $settings_bac);
			} else {
				// Backwards compatibility, version 1 -> current
				// Basically adds three special pages to the VVZ loop.
				$settings->pages[] = (object) array(
						"type" => "bac_prijslijst"
					);
				$settings->pages[] = (object) array(
						"type" => "bac_schandpaal"
					);
				$settings->pages[] = (object) array(
						"type" => "bac_schandpaal2"
					);
				$settings->pages[] = (object) array(
						"type" => "bac_schandpaal3"
					);
				$settings->pages[] = (object) array(
						"type" => "bac_biergrafiek"
					);
				$settings->pages[] = (object) array(
						"type" => "bac_statistieken"
					);
				$settings->pages[] = (object) array(
						"type" => "bac_rooster"
					);
			}
		}
} catch(Exception $e) {
}

// May 4th memorial clock
$memorial_start = new DateTime("2017-05-04 19:56:30");
$memorial_end = new DateTime("2017-05-04 20:03:00");
$now = new DateTime();
if ($memorial_start->getTimestamp() <= $now->getTimestamp() && $now->getTimestamp() < $memorial_end->getTimestamp()) {
	$settings->pages = array();
	$settings->pages[] = (object) array(
			"type" => "clock",
			"timeout" => max($memorial_end->getTimestamp() - $now->getTimestamp(), 5)
		);
}

// Settings sanitizing
if (!in_array($settings->skin, scandir("skin"))) {
	$settings->skin = "gewis";
}
if (empty($settings->timeout) || $settings->timeout <= 0 || (int)$settings->timeout != $settings->timeout) {
	$settings->timeout = 20;
}

// If settings are requested, return only the settings.
if (array_key_exists("settings_dump", $_GET)) {
	// Not used by the script, but human readable. Super handy!
	var_dump($settings);
	exit(0);
}
if (array_key_exists("settings", $_GET)) {
	echo json_encode($settings);
	exit(0);
}

// Determine page
$index = array_key_exists("index", $_GET) ? (int)$_GET["index"] : -1;
if ($index < -1 || $index >= count($settings->pages)) {
	$index = 0;
}
if ($index == -1) {
	$page = (object) array(
			"type" => "avico"
		);
} else {
	$page = $settings->pages[$index];
}

$include = "pages". DIRECTORY_SEPARATOR;
if (!in_array($page->type .".php", scandir($include))) {
	$page->type = "logo";
}
if (array_key_exists("test", $_GET)) {
	$page->type = $_GET["test"];
	//TESTSTUFF
	if ($page->type == "external") {
		$page->source = $_GET["source"];
	}
}
$include .= $page->type . ".php";

if (array_key_exists("type", $_GET) && $page->type != $_GET["type"] && $page->type != "avico") {
	// Screen has outdated settings loaded.
	die("RELOAD");
}

// If content is requested, return only content.
if (array_key_exists("content", $_GET)) {
	include $include;
	exit(0);
}

// Return TVPC app
?>
<!DOCTYPE html>
<html>
<head>
	<title>GEWIS TVPC</title>
	<meta charset="utf-8" />
	<meta name="theme-color" content="#d40026" />
	
	<link rel="stylesheet" href="<?=$path?>/skin/<?=$settings->skin?>/style.css" type="text/css" media="screen" id="skincss" />
	<link rel="stylesheet" href="<?=$path?>/css/style.css" type="text/css" media="screen" />
	<link rel="icon" type="image/x-icon" href="/favicon.ico" />
</head>

<body>

	<div id="tvpc-content">
		<?php include $include; ?>
	</div>
	
	<div id="tvpc-time" onclick="javascript:tvpcUpdate();">
		--:--
	</div>
	
	<script src="<?=$path?>/js/jquery-2.2.2.min.js"></script>
	<script type="text/javascript">
		var skin = "<?=$settings->skin?>";
		var path = "<?=$path?>";
		var tvpcSpeed = <?=array_key_exists("speed", $_GET)?(int)$_GET["speed"]:1?>;
	</script>
	<script src="<?=$path?>/js/tvpc.js"></script>
</body>
</html>
