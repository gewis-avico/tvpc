<h1>GEWIS Facebook</h1>
<ul>
<?php
$data = json_decode(file_get_contents("https://graph.facebook.com/svgewis/feed?access_token=".$facebook_token));
$post = $data->data[0];
for ($i = 1; $post->message == ""; $i++)
    $post = $data->data[$i];
?>
<li><?=$post->message;?></li>
</ul>
