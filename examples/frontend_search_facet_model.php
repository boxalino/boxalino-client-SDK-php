<html>
<head>
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script language="javascript">
$(document).ready(function(){
	$(".header").click(function () {

		$header = $(this);
		//getting the next element
		$content = $header.next();
		//open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
		$content.slideToggle(500, function () {
			
		});

	});
	
	$(".show_more_values").click(function () {

		$header = $(this);
		//getting the next element
		$content = $header.parent().find('.additional_values_1');
		//open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
		$content.slideToggle(500, function () {
			//execute this after slideToggle is done
			//change text of header based on visibility of content div
			$header.text(function () {
				//change text based on condition
				return $content.is(":visible") ? "less values" : "more values";
			});
		});

	});
});
</script>
<link rel="stylesheet" href="http://fontawesome.io/assets/font-awesome/css/font-awesome.css">
</head><body>
<?php

function displayFacet($logs, $facetField, $facets) {
	if($facets->isFacetHidden($facetField)) {
		return;
	}
	$icon = "";
	if($facets->getFacetIcon($facetField) != "") {
		$icon = "<i class=\"" . $facets->getFacetIcon($facetField) . "\"></i>";
	}
	$logs[] = "<div class=\"container\">";
	$logs[] = "<div class=\"header\">$icon <span>$facetField</span></div>";
	$logs[] = "<div class=\"content\" style=\"" . ($facets->isFacetExpanded($facetField) ? '' : 'display:none') . "\"><ul>";
	$showedMoreLink = false;
	foreach($facets->getFacetValues($facetField) as $fieldValue) {
		$postfix = "";
		if($facets->showFacetValueCounters($facetField)) {
			$postfix = " (" . $facets->getFacetValueCount($facetField, $fieldValue) . ")";
		}
		if($facets->isFacetValueSelected($facetField, $fieldValue)) {
			$postfix .= "<a href=\"?\">[X]</a>";
		}
		if(!$showedMoreLink && $facets->isFacetValueHidden($facetField, $fieldValue)) {
			$showedMoreLink = true;
			$logs[] = "<li class=\"show_more_values\">more values</li>";
		}
		$logs[] = "<li class=\"additional_values_$showedMoreLink\" style=\"" . ($showedMoreLink ? 'display:none' : '') . "\"><a href=\"?bx_" . $facetField . "=" . $facets->getFacetValueParameterValue($facetField, $fieldValue) . "\">" . $facets->getFacetValueLabel($facetField, $fieldValue) . "</a>$postfix</li>";
	}
	$logs[] = "</ul></div></div>";
	return $logs;
}

/**
* In this example, we make a simple search query, request a facet and get the search results and print the facet values and counter.
* We also implement a simple link logic so that if the user clicks on one of the facet values the page is reloaded with the results with this facet value selected.
*/

//include the Boxalino Client SDK php files
$libPath = '../lib'; //path to the lib folder with the Boxalino Client SDK and PHP Thrift Client files
require_once(__DIR__ . '/../vendor/autoload.php');
use com\boxalino\bxclient\v1\BxClient;
use com\boxalino\bxclient\v1\BxSearchRequest;
use com\boxalino\bxclient\v1\BxFacets;
BxClient::LOAD_CLASSES($libPath);

//required parameters you should set for this example to work
//$account = ""; // your account name
//$password = ""; // your account password
$domain = ""; // your web-site domain (e.g.: www.abc.com)
$logs = array(); //optional, just used here in example to collect logs
$isDev = true;
$host = isset($host) ? $host : "cdn.bx-cloud.com";

//Create the Boxalino Client SDK instance
//N.B.: you should not create several instances of BxClient on the same page, make sure to save it in a static variable and to re-use it.
$bxClient = new BxClient($account, $password, $domain, $isDev, $host);

$bxClient->setTestMode(true);
if(isset($timeout)) {
    $bxClient->setCurlTimeout($timeout);
}
try {
	$language = "de"; // a valid language code (e.g.: "en", "fr", "de", "it", ...)
	$queryText = ""; // a search query
	$hitCount = 10; //a maximum number of search result to return in one page
	
	//create search request
	$bxRequest = new BxSearchRequest($language, $queryText, $hitCount);
	
	$bxRequest->setReturnFields(array('title'));
	
	$fields = array("product_colors", "product_brand");
	
	//add a facert
	$facets = new BxFacets();
	foreach($fields as $field) {
		$facets->addFacet($field);
	}
	$bxRequest->setFacets($facets);
	
	//add the request
	$bxClient->addRequest($bxRequest);
	
	//make the query to Boxalino server and get back the response for all requests
	$bxResponse = $bxClient->getResponse();

	//get the facet responses
	$facets = $bxResponse->getFacets();
	
	//loop on the search response hit ids and print them
	$logs[] = "<table border=\"1\"><tr valign=\"top\"><td><h1>LEFT</h1>";
	foreach($facets->getLeftFacets() as $facetField) {
		$logs = displayFacet($logs, $facetField, $facets);
	}
	$logs[] = "</td><td><h1>TOP</h1>";
	foreach($facets->getTopFacets() as $facetField) {
		$logs = displayFacet($logs, $facetField, $facets);
	}
	$logs[] = "<h1>CENTER</h1>";
	//loop on the search response hit ids and print them
	foreach($bxResponse->getHitFieldValues(array('title')) as $id => $fieldValueMap) {
		$logs[] = "<h3>$id</h3>";
		foreach($fieldValueMap as $fieldName => $fieldValues) {
			$logs[] = "$fieldName: " . implode(',', $fieldValues);
		}
	}
	$logs[] = "<h1>BOTTOM</h1>";
	foreach($facets->getBottomFacets() as $facetField) {
		$logs = displayFacet($logs, $facetField, $facets);
	}
	$logs[] = "</td><td><h1>RIGHT</h1>";
	foreach($facets->getRightFacets() as $facetField) {
		$logs = displayFacet($logs, $facetField, $facets);
	}
	$logs[] = "</td></tr></table>";

	if(!isset($print) || $print){
		echo implode("", $logs);
	}
	
} catch(\Exception $e) {
	
	//be careful not to print the error message on your publish web-site as sensitive information like credentials might be indicated for debug purposes
	$exception = $e->getMessage();
	if(!isset($print) || $print) {
		var_dump($exception);
	}
}
?></body></html>