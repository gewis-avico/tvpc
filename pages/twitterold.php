<h1>GEWIS Tweets</h1>

<ul>
<?php
$data = json_decode(file_get_contents("http://search.twitter.com/search.json?q=%23gewis%20OR%20%23svgewis%20OR%20%40svGEWIS%20OR%20from%3AsvGEWIS&rpp=6"));

foreach ($data->results as $tweet) {
?>
<li>
 <img src="<?=str_replace("_normal.jpg", "_reasonably_small.jpg", $tweet->profile_image_url)?>" style="float: left; width: 2em; height: 2em; margin: .2em .4em 0em 0em;" />
 <div class="header">@<?=$tweet->from_user;?></div>
 <?=$tweet->text;?>
</li>
<?php
}
?>
</ul>