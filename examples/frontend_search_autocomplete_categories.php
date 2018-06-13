<?php

/**
* In this example, we make a simple search autocomplete query, get the textual search suggestions
*/

//include the Boxalino Client SDK php files
$libPath = '../lib'; //path to the lib folder with the Boxalino Client SDK and PHP Thrift Client files
require_once(__DIR__ . '/../vendor/autoload.php');
use com\boxalino\bxclient\v1\BxClient;
use com\boxalino\bxclient\v1\BxAutocompleteRequest;
use com\boxalino\bxclient\v1\BxFacets;
BxClient::LOAD_CLASSES($libPath);

//required parameters you should set for this example to work
$account = ""; // your account name
$password = ""; // your account password
$domain = ""; // your web-site domain (e.g.: www.abc.com)
$logs = array(); //optional, just used here in example to collect logs
$isDev = false;
$host = isset($host) ? $host : "cdn.bx-cloud.com";

//Create the Boxalino Client SDK instance
//N.B.: you should not create several instances of BxClient on the same page, make sure to save it in a static variable and to re-use it.
$bxClient = new BxClient($account, $password, $domain, $isDev, $host);
if(isset($timeout)) {
    $bxClient->setCurlTimeout($timeout);
}
try {
	$language = "en"; // a valid language code (e.g.: "en", "fr", "de", "it", ...)
	$queryText = "whit"; // a search query to be completed
	$textualSuggestionsHitCount = 10; //a maximum number of search textual suggestions to return in one page

	//create search request
	$bxRequest = new BxAutocompleteRequest($language, $queryText, $textualSuggestionsHitCount);
	
	$bxSearchRequest = $bxRequest->getBxSearchRequest();
	
	$facets = new BxFacets();
	$facets->addCategoryFacet();
	$bxSearchRequest->setFacets($facets);
	
	//set the request
	$bxClient->setAutocompleteRequest($bxRequest);
	
	//make the query to Boxalino server and get back the response for all requests
	$bxAutocompleteResponse = $bxClient->getAutocompleteResponse();
	
	//loop on the search response hit ids and print them
	$logs[] = "textual suggestions for \"$queryText\":";
	$i = 0;
	foreach($bxAutocompleteResponse->getTextualSuggestions() as $suggestion) {
		$logs[] = $bxAutocompleteResponse->getTextualSuggestionHighlighted($suggestion);
		if($i == 0) {
			foreach($bxAutocompleteResponse->getTextualSuggestionFacets($suggestion)->getCategories() as $value) {
				$logs[] = "<a href=\"?bx_category_id=" . $facets->getCategoryValueId($value) . "\">" . $facets->getCategoryValueLabel($value) . "</a> (" . $facets->getCategoryValueCount($value) . ")";
			}
		}
		$i++;
	}
	
	if(sizeof($bxAutocompleteResponse->getTextualSuggestions()) == 0) {
		$logs[] = "There are no autocomplete textual suggestions. This might be normal, but it also might mean that the first execution of the autocomplete index preparation was not done and published yet. Please refer to the example backend_data_init and make sure you have done the following steps at least once: 1) publish your data 2) run the prepareAutocomplete case 3) publish your data again";
	}

	if(!isset($print) || $print){
		echo implode("<br>", $logs);
	}
} catch(\Exception $e) {
	
	//be careful not to print the error message on your publish web-site as sensitive information like credentials might be indicated for debug purposes
	$exception = $e->getMessage();
	if(!isset($print) || $print){
		echo $exception;
	}
}
