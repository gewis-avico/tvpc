<?php
	$data = json_decode(file_get_contents("https://graph.facebook.com/svgewis/feed?access_token=405837926141542|sClZ7mufhasragZtEhY1FcX0CkE"));
	$post = $data->data[0];
	for ($i = 1; $post->message == ""; $i++) {
		$post = $data->data[$i];
	}
?>

<article class="facebook">

    <h1>
    	<img src="<?=$path?>/img/facebook.png" style="height: 1em; margin-right: 0.4em; vertical-align: middle;" />
        GEWIS Facebook
    </h1>
    
    <hr />
    
    <p><?=str_replace("\n", "<br />", htmlentities($post->message))?></p>

</article>