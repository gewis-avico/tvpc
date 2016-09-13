<?php

$subindex = array_key_exists("subindex", $_GET) ? (int)$_GET["subindex"] : 0;
if ($subindex < 0 || $subindex >= count($page->posters)) {
	$subindex = 0;
}

?>
<img class="poster" src="<?=$page->posters[$subindex]?>" />