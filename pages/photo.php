<?php
require_once('tokens.php');

if (!isset($settings)){
	include(dirname(__FILE__).'/../new/functions.inc.php');
	$settings = loadSettings();
}
$nxp = 'false';
if (isset($settings['pages'][$_GET['p']+1]) && $settings['pages'][$_GET['p']+1] == 'photo'){
	$nxp = 'true';
}

$photos = [];
foreach ($settings['galleries'] as $gal) {
    $ch = curl_init('https://gewis.nl/api/photo/album/' . $gal);

    curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Auth-Token: ' . $gewis_token]);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $gallery = json_decode(curl_exec($ch));
    curl_close($ch);

    foreach ($gallery->photos as $photo) {
        $photos[] = $photo;
    }
}

//echo '<pre>';
//var_dump($photos);
//echo '</pre>';

$photo = $photos[rand(0, count($photos) - 1)];
$url = '/data/' . $photo->path;



?>
<div class="fotocontainer" style="text-align:left" >
<img src="<?= $url ?>" id="photoContainer<?=$photo->id ?>" nxp="<?= $nxp ?>" style="visibility: hidden;" cur-id="<?=$photo->id ?>">
<script>
	$("#photoContainer<?= $photo->id ?>").load(function(){
		
		var fw = $(this).width();
		var sw = $(window).width();
		var mar = (sw-fw)/2;
		$(this).css('margin-left',mar);
		$(this).css('visibility','visible');
	});
</script>
</div>
