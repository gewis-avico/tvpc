<?php
echo file_get_contents('http://gewis.nl/~avico/tvpc/pages/randominfima.php');
return;
//werkt niet meer, twitter is van api verandert
//echo file_get_contents("http://search.twitter.com/search.json?q=%23gewis%20OR%20%23svgewis%20OR%20%40svGEWIS%20OR%20from%3AsvGEWIS&rpp=6");
$data = json_decode(file_get_contents("http://search.twitter.com/search.json?q=%23gewis%20OR%20%23svgewis%20OR%20%40svGEWIS%20OR%20from%3AsvGEWIS&include_entities=true&rpp=6"));
//$blocked = NULL;

$blocked = json_decode(file_get_contents('http://gewis.nl/~avico/tvpc/pages/twitterblocks.txt'),true);

foreach ($data->results as $tweet) {
if ((isset($blocked)&&!in_array(($tweet->from_user),$blocked["blocks"]))||!isset($blocked)){
?>
<li>
 <img src="<?=str_replace("_normal.jpg", "_reasonably_small.jpg", $tweet->profile_image_url)?>" style="float: left; width: 2em; height: 2em; margin: .2em .4em 0em 0em;" />
 <div class="header">@<?=$tweet->from_user;?></div>
 <?
$txte=$tweet->text; 

if (strpos($txte,'http://'))
{
$ff = strpos($txte,'http://');
$yy = substr($txte,$ff);
$ff = strpos($yy, ' ');
if ($ff)
{
$yy = substr($yy, 0, $ff);
}
$txte = str_replace($yy, ' ', $txte);
$ent = $tweet->entities;
if ($ent->media[0])
{
$txte = $txte.'<image src="'.$ent->media[0]->media_url.'"style="width: 3em; float:right; height: 2em; margin: 0em 3em 0em 0em;" />';

}
echo $txte;
}
else
{
echo $txte;
}

?>
</li>
<?php
}}
?>
