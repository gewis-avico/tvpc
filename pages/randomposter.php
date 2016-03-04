<?php
$nposters = count($settings['randomposters']);
$poster = mt_rand(0,$nposters-1);
if ($nposters>0)
$image = $settings['randomposters'][$poster];
?>
<img class="poster" src="<?=$image;?>">
