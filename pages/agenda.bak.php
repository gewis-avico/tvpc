<?php
$db = db_get_object("agenda");

$query = "SELECT Activiteit,ActiviteitEng,Datum,UNIX_TIMESTAMP(Datum) AS datum_unix,TotDatum,UNIX_TIMESTAMP(TotDatum) AS totdatum_unix FROM agenda"
       ." WHERE Display = 1 AND ((Datum >= DATE(NOW())) OR (TotDatum >= DATE(NOW())))"
       ." ORDER BY Datum ASC LIMIT 0,4";
$res = $db->query($query);
?>

<h1>GEWIS Agenda</h1>
<ul>

<?php
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
?>

<li>
 <div class="header"><?=$date;?></div>
 <?=htmlentities($name);?>
</li>

<?php
}
?>

</ul>
