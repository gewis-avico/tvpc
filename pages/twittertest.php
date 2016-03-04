<?php
require_once('tokens.php');
$url = 'https://api.twitter.com/oauth2/token';

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
	'POST /oauth2/token HTTP/1.1',
        'header'  => "Content-type: application/x-www-form-urlencoded;charset=UTF-8 Authorization: Basic ".$twitter_token,
        'method'  => 'POST',
    ),
);
$context  = stream_context_create($options,array("grant_type=client_credentials"));
$result = file_get_contents($url, false, $context);

var_dump($result);
?>