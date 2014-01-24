<?php include("header.php"); ?>
<div style="clear: both"></div>

<div id="content">

<!-- Take the user input, store it for later use, fold accents, lowercase it (or simultaneously fold accents and case?), strip HTML tags since none should be included in a search anyhow, and run htmlentities on it to kill anything resembling XSS.

Then we have to parse the JSON. Probably just read in the whole file via json_decode, but don't parse to Signer objects until we display. That lets us rely on PHP's JSON parsing, rather than cooking our own function to search through an array of Signers or something.
-->

<?php 
require_once("SignerClass.php");
require_once("AccentFold.php");

if(sizeof($_GET)) {
	$printsearch=trim(strip_tags($_GET["searchterm"])); 
	// Down the line, may want to allow boolean operators. Check other models, but easiest would be an array of arrays, with each main array being ORed, a subarray being ANDed. But we might have to build quite the tree, then navigate it. For now, just get search working, then refine it. Set aside $printsearch to put back in the search box ultimately, applying html_entities at that last moment in case we want to do anything more with the original text.
}

print '<form action="search.php" method="get">Search signers names, congregations, denominations, cities, states: <input type="text" name="searchterm" value="'.htmlentities($printsearch).'"> <br /> <input type="submit"> </form>';

if(sizeof($_GET)) {
	print '<hr />';

// regex_escape and quoted_explode via Brilliand @ SO http://stackoverflow.com/a/13755505
	function regex_escape($subject) {
	    return str_replace(array('\\', '^', '-', ']'), array('\\\\', '\\^', '\\-', '\\]'), $subject);
	}

	function quoted_explode($subject, $delimiters = ',', $quotes = '\'') {
    	$clauses[] = '[^'.regex_escape($delimiters.$quotes).']';
    	foreach(str_split($quotes) as $quote) {
    	    $quote = regex_escape($quote);
    	    $clauses[] = "[$quote][^$quote]*[$quote]";
    	}
    	$regex = '(?:'.implode('|', $clauses).')+';
    	preg_match_all('/'.str_replace('/', '\\/', $regex).'/', $subject, $matches);
    	return $matches[0];
	}

/* The Plan™: Having made the search term safe for human consumption and broken it into chunks by spaces or quoted blocks, we'll iterate through the entries, matching each array element. Match numbers against ZIP and Congregation only. In checking against state and country, be sure to try converting the search text using the standard converters in SignerClass. Right now, we only care if this matches "close enough," so can basically start with the shortest item in $tests and work up, running through each searchable field. If an item matches sufficiently, pass the Signer object to an array for later printing. Or, shit, just print it. But then we can't sort. Should sort be configurable on this page?
*/

	$testphrase=AccentFold::accent_case_fold($printsearch);
	$tests=quoted_explode($testphrase,' ','"\'');

//

//Let's remove punctuation, too, but worry about that later
	$signers=json_decode(file_get_contents("entries.json"),true)['Entries'];  

// Not doing anything to remove undisplayed fields. Could do that here, or as a way to fail out of the foreach loop. Maybe no harm in letting ZIP code through, while address and email shouldn't be searched. That leaves first_name, last_name, name(), congregation, city, state, zip, country, religion, denomination

	foreach ($signers as $s) {
		$testsign=new Signer($s);
		$shortest=-1;
		foreach($s as $key=>$val) {
			if(is_string($val)) {  // Everything in the JSON file is stored as a string.
				$strdist=levenshtein(AccentFold::accent_case_fold($val), $testphrase);
			} 
// If we allow ZIP code searching, need to separate that out since Levenshtein distance will produce nonsense. Similarly, state could run through the SignerClass comparison operator when comparing to the state key (Field6), and we might create something similar for country abbreviations
			if($strdist<=$shortest || $shortest<0) {$shortest=$strdist;}
		}
		$dists[]=$shortest;
	}

	define('CUTOFF',2);

	$assessmatch=function($s,$d){if ($d<0) {return -1;} if ($d <= CUTOFF) {return 0;} else {return +1;}};

	function gtCUT($gt){return $gt<=CUTOFF;}

	if(sizeof(array_filter($dists,"gtCUT"))>0) {
		$displaysigners=array_uintersect_assoc($signers, $dists, $assessmatch);
		foreach($displaysigners as $i) {print (new Signer($i))->htmlize();} 
	} else { print '<p>No results found. Try a different search or look at <a href="/list.php">the full list</a>.</p>';}
}
?>

</div>

<!-- Maybe come back to this idea, an AJAX search that updates live. Keep non-live as noscript backup.
 <script>
function showResult(str)
{
if (str.length==0)
  { 
  document.getElementById("livesearch").innerHTML="";
  document.getElementById("livesearch").style.border="0px";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("livesearch").innerHTML=xmlhttp.responseText;
    document.getElementById("livesearch").style.border="1px solid #A5ACB2";
    }
  }
xmlhttp.open("GET","livesearch.php?q="+str,true);
xmlhttp.send();
}
</script>

<div id="content">

<form>
<input type="text" size="30" onkeyup="showResult(this.value)">
<div id="livesearch"></div>
</form> 

</div> -->

<?php include("sidebar.php");?>

<?php include('footer.php'); ?>


