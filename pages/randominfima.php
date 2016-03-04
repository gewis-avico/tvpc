<html>
<font face="Segoe UI">
<?php
if (intval(date("d"))==7 && intval(date("m")) == 11 && intval(date("Y"))==2013)
{
	?>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<div style="transition:all .2s;height:100%;width:100%;background:rgba(255,0,0,.5)">
<div style="margin:20px;float:left;position:relative;top:8px;left:8px;max-width:25%; max-height:25%;"><img src="http://burntech.tv/wp-content/uploads/2013/06/6c65e704ce82f8a0c01ce08e5c6fe684.png" style="max-width:100%"></img></div>
<div id="warning" style="transition:all 1.4s;width:50%; text-align:center; margin-left:auto; margin-right:auto;font-size:250%; font-weight:800; line-height:280%">Warning!</div>
<div style="width:100%; margin:4px;margin-bottom:30px; text-align:center; margin-left:auto; margin-right:auto;font-size:140%; font-weith:600; line-height:100%">Tonight there's an emergency ordinance active in the center of Eindhoven</div>
<div style="width:100%;margin:4px; text-align:center; margin-left:auto; margin-right:auto;font-size:110%; line-height:100%">Some soccer might try to trash the city so watch yourselves, there will be a lot of police!</div>
</div>
<script>
function changeYellow()
{
$("#warning").css("color","red");
setTimeout(function(){changeRed();},700);
}
function changeRed(){

$("#warning").css("color","yellow");
setTimeout(function(){changeYellow();},700);
}
$(document).ready(function(){

changeRed();});
</script>
	
	<?
}
else
{
$html = file_get_contents("http://gewis.nl/~supremum/infima/list.php");
$html = substr($html,strpos($html,"<body"));
$html = substr($html,strpos($html,'>')+1);
$html = substr($html,0,strpos($html,"</body"));
$infima = explode("</i>",$html);

echo '<div style="font-size:60px;">';

echo '<p style="margin:10px 30px;">';
$inf = $infima[mt_rand(0,150)];
$inf2 = $infima[mt_rand(150,sizeof($infima)-1)];
if ((strlen($inf)+strlen($inf2))<=350)
{
echo $inf;
echo '</i>';
echo '</p><p style="margin:10px 30px;">';
echo $inf2;
echo '</i>';
}
else if (strlen($inf)>strlen($inf2))
{
echo $inf;
echo '</i>';
}
else
{
echo $inf2;
echo '</i>';
}
echo "</p>";

echo '</div>';
echo '<div style="font-size:40px;margin:0px 30px;color:#CCCCCC; position: fixed; bottom: 0; left: 0;">Ook een leuke uitspraak van een GEWIS\'er gehoord? Ga naar inf.gewis.nl en stuur hem in!</div>';
}
?>
</font>
</html>