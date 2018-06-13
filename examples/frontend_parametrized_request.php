<?php

/**
* In this example, we make a advanced implementation case for a parmaeterized request (all the url parameters are automatically detected and loaded and the current state retrievable)
*/

//include the Boxalino Client SDK php files
$libPath = '../lib'; //path to the lib folder with the Boxalino Client SDK and PHP Thrift Client files
require_once(__DIR__ . '/../vendor/autoload.php');
use com\boxalino\bxclient\v1\BxClient;
use com\boxalino\bxclient\v1\BxParametrizedRequest;
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
$bxClient->setRequestMap($_REQUEST);
if(isset($timeout)) {
    $bxClient->setCurlTimeout($timeout);
}
function getItemFieldsCB($ids, $fieldNames) {
	//todo your code here to retrieve the fields values
	$values = array();
	foreach($ids as $id) {
		$values[$id] = array();
		foreach($fieldNames as $fieldName) {
			$values[$id][$fieldName] = array($fieldName . "-value");
		}
	}
	return $values;
}

try {
	$language = "en"; // a valid language code (e.g.: "en", "fr", "de", "it", ...)
	$choiceId = "productfinder"; //the recommendation choice id (standard choice ids are: "similar" => similar products on product detail page, "complementary" => complementary products on product detail page, "basket" => cross-selling recommendations on basket page, "search"=>search results, "home" => home page personalized suggestions, "category" => category page suggestions, "navigation" => navigation product listing pages suggestions)
	$hitCount = 10; //a maximum number of recommended result to return in one page
	$requestWeightedParametersPrefix = "bxrpw_";
	$requestFiltersPrefix = "bxfi_";
	$requestFacetsPrefix = "bxfa_";
	$requestSortFieldPrefix = "bxsf_";
	$requestReturnFieldsName= "bxrf";
	
	$bxReturnFields = array('id'); //the list of fields which should be returned directly by Boxalino, the others will be retrieved through a call-back function
	$getItemFieldsCB = "getItemFieldsCB";
	
	//create the request and set the parameter prefix values
	$bxRequest = new BxParametrizedRequest($language, $choiceId, $hitCount, 0, $bxReturnFields, $getItemFieldsCB);
	$bxRequest->setRequestWeightedParametersPrefix($requestWeightedParametersPrefix);
	$bxRequest->setRequestFiltersPrefix($requestFiltersPrefix);
	$bxRequest->setRequestFacetsPrefix($requestFacetsPrefix);
	$bxRequest->setRequestSortFieldPrefix($requestSortFieldPrefix);
	
	$bxRequest->setRequestReturnFieldsName($requestReturnFieldsName);
	
	//add the request
	$bxClient->addRequest($bxRequest);
	
	//make the query to Boxalino server and get back the response for all requests
	$bxResponse = $bxClient->getResponse();
	$logs[] = "<h3>weighted parameters</h3>";
	foreach($bxRequest->getWeightedParameters() as $fieldName => $fieldValues) {
		foreach($fieldValues as $fieldValue => $weight) {
			$logs[] = "$fieldName: $fieldValue: $weight";
		}
	}
	$logs[] = "..";
	
	$logs[] = "<h3>filters</h3>";
	foreach($bxRequest->getFilters() as $bxFilter) {
		$logs[] = $bxFilter->getFieldName() . ": " . implode(',', $bxFilter->getValues()) . " :" . $bxFilter->isNegative();
	}
	$logs[] = "..";
	
	$logs[] = "<h3>facets</h3>";
	$bxFacets = $bxRequest->getFacets();
	foreach($bxFacets->getFieldNames() as $fieldName) {
		$logs[] = "$fieldName: " . implode(',', $bxFacets->getSelectedValues($fieldName));
	}
	$logs[] = "..";
	
	$logs[] = "<h3>sort fields</h3>";
	$bxSortFields = $bxRequest->getSortFields();
	foreach($bxSortFields->getSortFields() as $fieldName) {
		$logs[] = "$fieldName: " . $bxSortFields->isFieldReverse($fieldName);;
	}
	$logs[] = "..";
	
	//loop on the recommended response hit ids and print them
	$logs[] = "<h3>results</h3>";
	$logs[] = $bxResponse->toJson($bxRequest->getAllReturnFields());

	if(!isset($print) || $print){
		echo implode("<br>",$logs);
	}

} catch(\Exception $e) {
	
	//be careful not to print the error message on your publish web-site as sensitive information like credentials might be indicated for debug purposes
	$exception = $e->getMessage();
	if(!isset($print) || $print){
		echo $exception;
	}
}
