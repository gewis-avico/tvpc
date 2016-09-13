<?php

// Get activities
$ch = curl_init('https://gewis.nl/api/activity/list');

curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Auth-Token: ". $api_token));
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$ch_result = curl_exec($ch);
curl_close($ch);

$activities = json_decode($ch_result);
$activities = array_slice($activities, 0, 3);

?>

<article class="agenda">
    <h1>Activities</h1>
    
    <hr />
    
    <ul>
		<?php
        foreach ($activities as $activity) {
        
            $name = !empty($activity->nameEn) ? $activity->nameEn : $activity->name;
            
            $beginTime = new \DateTime($activity->beginTime->date);
            $endTime = NULL;
            $date = $beginTime->format('l, F jS');
            
            if ($activity->endTime !== NULL) {
                $endTime = new \DateTime($activity->endTime->date);
				if ($beginTime->format("F jS") != $endTime->format("F jS")) {
                	$date .= $endTime->format(' - l, F jS');
				}
            }
        ?>

            <li>
                <h2><?=htmlspecialchars($name)?></h2>
                <p><?=$date?></p>
            </li>

		<?php } ?>
    </ul>
</article>