<?php

$photos = array();
foreach ($page->galleries as $gal) {
    $ch = curl_init('https://gewis.nl/api/photo/album/' . $gal);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Auth-Token: '. $api_token));
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$ch_result = curl_exec($ch);

    curl_close($ch);
	
    $gallery = json_decode($ch_result);
    $photos = array_merge($photos, $gallery->photos);
}

$photo = $photos[rand(0, count($photos) - 1)];
$date = new \DateTime($photo->dateTime->date);

if (array_key_exists("content", $_GET)) {
	$json = array(
			"path" => "/data/". $photo->path,
			"name" => $photo->album->name,
			"date" => $date->format('l, F jS')
		);
	echo json_encode($json);
	exit(0);
}

?>

<article class="photo" style="background-image: url('/data/<?=$photo->path?>');">
	<p>
		<?=htmlentities($photo->album->name)?>
        <span><?=$date->format('l, F jS')?></span>
    </p>
</article>