<?php
require_once('tokens.php');

$ch = curl_init('https://gewis.nl/api/activity/list');

curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Auth-Token: ' . $gewis_token]);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$activities = json_decode(curl_exec($ch));
// only show first 3 elements
$activities = array_slice($activities, 0, 3);
curl_close($ch);

//$db = db_get_object("agenda");

//$query = "SELECT Activiteit,ActiviteitEng,Datum,UNIX_TIMESTAMP(Datum) AS datum_unix,NULLIF(TotDatum,Datum) AS TotDatum,UNIX_TIMESTAMP(NULLIF(TotDatum,Datum)) AS totdatum_unix FROM agenda"
       //." WHERE Display = 1 AND Eetlijst = 0 AND ((Datum >= DATE(NOW())) OR (TotDatum >= DATE(NOW())))"
       //." ORDER BY Datum ASC LIMIT 0,4";
//$res = $db->query($query);
?>

<h1>GEWIS Agenda</h1>
<ul>

<?php
foreach ($activities as $activity) {

    // Prefer English name if available
    $name = $activity->name;
    if (!empty($activity->nameEn))
        $name = $activity->nameEn;

    if ($activity->endTime === null) {
        $beginTime = new \DateTime($activity->beginTime->date);
        $date = $beginTime->format('l F jS');
    } else {
        $beginTime = new \DateTime($activity->beginTime->date);
        $endTime = new \DateTime($activity->endTime->date);
        $date = $beginTime->format('F jS') . ' - ' . $endTime->format('F jS');
    }
?>

<li>
 <div class="header"><?=$date;?></div>
 <?=htmlspecialchars($name);?>
</li>

<?php
}
/*
for ($i = 0; $i < $res->num_rows(); $i++)
{
    $row = mysql_fetch_assoc($res->result);
    
    // Prefer English name if available
    $name = $row['Activiteit'];
    if (!empty($row['ActiviteitEng']))
        $name = $row['ActiviteitEng'];
    
    if ($row['TotDatum'] === null)
    {
        $date = date("l F jS", $row['datum_unix']);
    }
    else
    {
        $date = date("F jS", $row['datum_unix']) . " - " . date("F jS", $row['totdatum_unix']);
    }    
}
 */
?>

</ul>
