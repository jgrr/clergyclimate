<?php include("header.php"); ?>

<script src="http://code.jquery.com/jquery-1.10.1.min.js"> </script>
<!-- <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script> -->
<script>jQuery(document).ready(function($) {
	
    var bigbrother = -1;
//    var widebrother = -1;

    $('.signer').each(function() {
      bigbrother = bigbrother > $('.signer').height() ? bigbrother : $('.signer').height();
//      widebrother = widebrother > $('.signer').width() ? widebrother : $('.signer').width();
    });

    $('.signer').each(function() {
      $('.signer').height(bigbrother);
//      $('.signer').width(widebrother);
    });
});</script>

<div style="clear: both"></div>

<div id="content">

<p>The following clergy members have signed the Clergy Climate Letter:</p>

<?php 
	require_once('WufooApiWrapper.php');
	require_once('SignerClass.php');

// API key: QMOT-QZVH-SBR7-F3LO
// https://ncse.wufoo.com/api/v3/forms/x8kqf1a14ye9db/entries.json?pretty=true
// https://ncse.wufoo.com/api/v3/reports/micq5e71iv25ow/entries.xml?pretty=true

$signers=json_decode(file_get_contents("entries.json"),true)['Entries'];

/* Get sort order
foreach ($signers as $key => $row) {
    $volume[$key]  = $row['volume'];
    $edition[$key] = $row['edition'];
}

array_multisort($signers[0], SORT_ASC, SORT_STRING,$ar[1], SORT_NUMERIC, SORT_DESC);
*/

// var_dump($signers);

// display name, church, city, state, style by religion

$state='.';
$nonUS; // how to sort this by country, etc.?
// $states=array($AL=>array(),$AZ=>array(),$AR=>array(),$CA=>array(),$CO=>array(),$CT=>array(),$DE=>array(),$DC=>array(),$FL=>array(),$GA=>array(),$HI=>array(),$ID=>array(),$IL=>array(),$IN=>array(),$IA=>array(),$KS=>array(),$KY=>array(),$LA=>array(),$ME=>array(),$MD=>array(),$MA=>array(),$MI=>array(),$MN=>array(),$MS=>array(),$MO=>array(),$MT=>array(),$NE=>array(),$NV=>array(),$NH=>array(),$NJ>array(),$NM=>array(),$NY=>array(),$NC=>array(),$ND=>array(),$OH=>array(),$OK=>array(),$OR=>array(),$PA=>array(),$RI=>array(),$SC=>array(),$SD=>array(),$TN=>array(),$TX=>array(),$UT=>array(),$VT=>array(),$VA=>array(),$WA=>array(),$WV=>array(),$WI=>array(),$WY=>array(),$PR=>array(),$AS=>array(),$GU=>array(),$MP=>array(),$VI=>array(),$UM=>array(),$FM=>array(),$MH=>array(),$PW=>array(),$AA=>array(),$AE=>array(),$AP=>array());

foreach ($signers as $signer) {
	$entries[]=new Signer($signer);
}

usort($entries,"Signer::cmp");

print "<h2 style=\"clear:left;\">Signers from the United States</h2>";
foreach ($entries as $entry) {
	if($entry->country()!="United States" && $entry->country()!="") {
		$nonUSholding.=$entry->htmlize();
	} else {
		if($state!=$entry->state()) {
			print "<hr style=\"clear:left;\" /> <h3 style=\"clear:left;\"><a id=\"".$entry->state()."\">".$entry->state()."</a></h3>";
			$state=$entry->state();
		}
		print $entry->htmlize();
	}
}
print "<hr style=\"clear:left;\" /> <h2 style=\"clear:left;\">Outside the US</h2>$nonUSholding";
	
$constants = get_defined_constants(true);
$json_errors = array();
foreach ($constants["json"] as $name => $value) {
	if (!strncmp($name, "JSON_ERROR_", 11)) {
		$json_errors[$value] = $name;
	}
}
?>
<!-- Can't embed a widget from Wufoo for this. Need to do some PHP trickery to extract it via Wufoo API, hopefully caching it, then format and display. Use same cache as for search.php? Or autogenerate this full list when the cache is created, then just pull that cached file into this. -->



</div>


<?php include("sidebar.php");?>

<?php include('footer.php'); ?>