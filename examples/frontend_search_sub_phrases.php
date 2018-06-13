<?php

/**
* In this example, we make a simple search query containing two keywords which both provides search results alone, but none together. Then we show the two sub-phrases groups to let the user chose to search for one or the other.
*/

//include the Boxalino Client SDK php files
$libPath = '../lib'; //path to the lib folder with the Boxalino Client SDK and PHP Thrift Client files
require_once(__DIR__ . '/../vendor/autoload.php');
use com\boxalino\bxclient\v1\BxClient;
use com\boxalino\bxclient\v1\BxSearchRequest;
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
	$queryText = "women pack"; // a search query
	$hitCount = 10; //a maximum number of search result to return in one page

	//create search request
	$bxRequest = new BxSearchRequest($language, $queryText, $hitCount);
	
	//add the request
	$bxClient->addRequest($bxRequest);
	
	//make the query to Boxalino server and get back the response for all requests
	$bxResponse = $bxClient->getResponse();
	
	if($bxResponse->areResultsCorrectedAndAlsoProvideSubPhrases()) {
		$logs[] = "Corrected query \"" . $queryText . "\" into \"" . $bxResponse->getCorrectedQuery() . "\"";
	}
	
	//check if the system has generated sub phrases results
	if($bxResponse->areThereSubPhrases()) {
		$logs[] = "No results found for all words in " . $queryText . ", but following partial matches were found:";
		foreach($bxResponse->getSubPhrasesQueries() as $subPhrase) {
			$logs[] = "Results for \"" . $subPhrase . "\" (" . $bxResponse->getSubPhraseTotalHitCount($subPhrase) . " hits):";
			//loop on the search response hit ids and print them
			foreach($bxResponse->getSubPhraseHitIds($subPhrase) as $i => $id) {
				$logs[] = "$i: returned id $id";
			}
			$logs[] = '';
		}
	} else {
		//loop on the search response hit ids and print them
		foreach($bxResponse->getHitIds() as $i => $id) {
			$logs[] =  "$i: returned id $id";
		}
	}
	if(!isset($print) || $print){
		echo implode("<br/>", $logs);
	}
	
} catch(\Exception $e) {
	
	//be careful not to print the error message on your publish web-site as sensitive information like credentials might be indicated for debug purposes
	$exception = $e->getMessage();
	if(!isset($print) || $print) {
		echo $exception;
	}
}
