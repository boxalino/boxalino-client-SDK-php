<?php

/**
* In this example, we make several search autocomplete queries, and for each get the textual search suggestions and the item suggestions for each textual suggestion and globally
*/

//include the Boxalino Client SDK php files
$libPath = '../lib'; //path to the lib folder with the Boxalino Client SDK and PHP Thrift Client files
require_once(__DIR__ . '/../vendor/autoload.php');
use com\boxalino\bxclient\v1\BxClient;
use com\boxalino\bxclient\v1\BxAutocompleteRequest;
BxClient::LOAD_CLASSES($libPath);

//required parameters you should set for this example to work
//$account = ""; // your account name
//$password = ""; // your account password
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
	$queryTexts = array("whit", "yello"); // a search query to be completed
	$textualSuggestionsHitCount = 10; //a maximum number of search textual suggestions to return in one page
	$fieldNames = array('title'); //return the title for each item returned (globally and per textual suggestion) - IMPORTANT: you need to put "products_" as a prefix to your field name except for standard fields: "title", "body", "discountedPrice", "standardPrice"

	$bxRequests = [];
	foreach ($queryTexts as $queryText) {
		//create search request
		$bxRequest = new BxAutocompleteRequest($language, $queryText, $textualSuggestionsHitCount);
		
		//N.B.: in case you would want to set a filter on a request and not another, you can simply do it by getting the searchchoicerequest with: $bxRequest->getBxSearchRequest() and adding a filter
		
		//set the fields to be returned for each item in the response
		$bxRequest->getBxSearchRequest()->setReturnFields($fieldNames);
		$bxRequests[] = $bxRequest;
	}
	
	//set the request
	$bxClient->setAutocompleteRequests($bxRequests);
	
	//make the query to Boxalino server and get back the response for all requests
	$bxAutocompleteResponses = $bxClient->getAutocompleteResponses();
	$i = -1;
	foreach ($bxAutocompleteResponses as $bxAutocompleteResponse) {

			//loop on the search response hit ids and print them
		$queryText = $queryTexts[++$i];
		$logs[] = "<h2>textual suggestions for \"$queryText\":</h2>";
		foreach($bxAutocompleteResponse->getTextualSuggestions() as $suggestion) {
			$logs[] = "<div style=\"border:1px solid; padding:10px; margin:10px\">";
			$logs[] = "<h3>$suggestion</b></h3>";

			$logs[] = "item suggestions for suggestion \"$suggestion\":";
			//loop on the search response hit ids and print them
			foreach($bxAutocompleteResponse->getBxSearchResponse($suggestion)->getHitFieldValues($fieldNames) as $id => $fieldValueMap) {
				$logs[] = "<div>$id";
				foreach($fieldValueMap as $fieldName => $fieldValues) {
					$logs[] = " - $fieldName: " . implode(',', $fieldValues) . "";
				}
				$logs[] = "</div>";
			}
			$logs[] = "</div>";
		}

		$logs[] = "<h2>global item suggestions for \"$queryText\":</h2>";
		//loop on the search response hit ids and print them
		foreach($bxAutocompleteResponse->getBxSearchResponse()->getHitFieldValues($fieldNames) as $id => $fieldValueMap) {
			$logs[] = "<div>$id";
			foreach($fieldValueMap as $fieldName => $fieldValues) {
				$logs[] = " - $fieldName: " . implode(',', $fieldValues) . "";
			}
			$logs[] = "</div>";
		}
	}

	if(!isset($print) ||$print){
		echo implode('', $logs);
	}

} catch(\Exception $e) {
	
	//be careful not to print the error message on your publish web-site as sensitive information like credentials might be indicated for debug purposes
	$exception = $e->getMessage();
	if(!isset($print) || $print){
		echo $exception;
	}
}
