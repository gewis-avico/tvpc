<?php

$ch = curl_init('https://webservices.ns.nl/ns-api-avt?station=eindhoven');

curl_setopt($ch, CURLOPT_USERPWD, 'willem@gewis.nl:V8hrQLM3FdN2blzhSY647zS9p1PTOOFyWy15o2eTrevhAbUd2U8rSQ');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

$ch_result = curl_exec($ch);
$xml = simplexml_load_string($ch_result);

$trains = $xml->VertrekkendeTrein;

?>
<article class="ns">

    <h1>
        Train Departures
    </h1>
    
    <hr />
    
    <table>
    	<?php
        	foreach($trains as $train) {
				$departure_scheduled = new DateTime($train->VertrekTijd);
				$departure_actual = new DateTime($train->VertrekTijd);
				if (!empty($train->VertrekVertraging)) {
					$departure_actual->add(new DateInterval($train->VertrekVertraging));
				}
				if (time() + 480 > $departure_actual->getTimestamp() || $train->Vervoerder != "NS") {
					continue;
				}
		?>
        	<tr>
            	<td class="departure"><?=$departure_scheduled->format("H:i")?></td>
            	<td class="delay"><?=!empty($train->VertrekVertraging)?htmlentities($train->VertrekVertragingTekst):"&nbsp;"?></td>
            	<td class="destination">
					<?=htmlentities($train->EindBestemming)?>
            		<span class="info"><?=$train->TreinSoort.(!empty($train->RouteTekst)?" via ".htmlentities($train->RouteTekst):"&nbsp;")?></span>
                </td>
            </tr>
    	<?php } ?>
    </table>
	<script src="<?=$path?>/js/jquery-2.2.2.min.js"></script>
	<script>
	// Display the page without cutting on a row
	$(document).ready(function(){
		var height = $(window).height();
		var rowLeft = $("table td").position().left; // Get screen size and table start (for x-coordinate)
		var lastRow1 = document.elementFromPoint(rowLeft, (height-5));
		var lastRow2 = document.elementFromPoint(rowLeft, (height-15)); // Select the TD's at two different positions so we don't remove the whole table
		if (lastRow1 == lastRow2) {
			var lastRow = lastRow1; // If the TD is indeed at the two positions we can use this one
		} else {
			var lastRow = document.elementFromPoint(rowLeft, (height-10)); // Else we take the TD which is in between
		}
		var lastRowID = $(lastRow).closest('tr').index(); // Get the row and its index
		if (lastRowID == -1) {
			lastRowID = lastRow.closest('tr').index();
		}
		$("article.ns table").find("tr:gt(" + (lastRowID-1) +")").remove(); // Remove rows starting at this row
	});
	</script>
</article>