<?php
$poster = 0;
if (isset($_GET['poster']) &&
    (intval($_GET['poster']) == $_GET['poster']) &&
    (intval($_GET['poster']) < count($settings['posters'])))
    $poster = intval($_GET['poster']);

$image = $settings['posters'][$poster];



if (substr($image, -3) == 'swf') {


	//Image is a flash file
?>
    <script type="text/javascript" src="http://coffee-online.googlecode.com/svn-history/r5/trunk/templates/com_trua/js/swfobject.js"></script>
	<script type="text/javascript">
    swfobject.embedSWF("<?=$image;?>", "myContent", "1920", "1080", "9.0.0");
    </script>
    <div id="myContent">
      <p>Flash poster not working :(</p>
    </div>
<?


} else {


	//Image is png, gif, etc
?>
	<img class="poster" src="<?=$image;?>">
	
<?

}
?>
