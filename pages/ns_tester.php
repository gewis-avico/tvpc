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
	// Functions to display the NS page without cutting
	$(document).ready(function(){
		var height = $(window).height();
		var rowLeft = $("table td").position().left;
		var lastRow1 = document.elementFromPoint(rowLeft, (height-5));
		var lastRow2 = document.elementFromPoint(rowLeft, (height-15));
		if (lastRow1 == lastRow2) {
			var lastRow = lastRow1;
		} else {
			var lastRow = document.elementFromPoint(rowLeft, (height-10));
		}
		var lastRowID = $(lastRow).closest('tr').index();
		if (lastRowID == -1) {
			lastRowID = lastRow.closest('tr').index();
		}
		$("article.ns table").find("tr:gt(" + (lastRowID-1) +")").remove();
	});
	</script>
</article>