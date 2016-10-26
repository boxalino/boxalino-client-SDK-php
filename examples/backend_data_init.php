<?php

/**
* In this example, we indicate the way to call some additional resources (refered to in other examples).
*/

//include the Boxalino Client SDK php files
$libPath = '../lib'; //path to the lib folder with the Boxalino Client SDK and PHP Thrift Client files
require_once(__DIR__ . '/../vendor/autoload.php');
use com\boxalino\bxclient\v1\BxClient;
use com\boxalino\bxclient\v1\BxData;
BxClient::LOAD_CLASSES($libPath);

//required parameters you should set for this example to work
//$account = ""; // your account name
//$password = ""; // your account password
$domain = ""; // your web-site domain (e.g.: www.abc.com)
$languages = array('en'); //declare the list of available languages
$isDev = false; //are the data to be pushed dev or prod data?
$isDelta = false; //are the data to be pushed full data (reset index) or delta (add/modify index)?
$logs = array(); //optional, just used here in example to collect logs

//Create the Boxalino Data SDK instance
$bxData = new BxData(new BxClient($account, $password, $domain), $languages, $isDev, $isDelta);

try {
	
	/**
	* Publish choices
	*/
	//your choie configuration can be generated in 3 possible ways: dev (using dev data), prod (using prod data as on your live web-site), prod-test (using prod data but not affecting your live web-site)
	$isTest = false;
	$logs[] = "force the publish of your choices configuration: it does it either for dev or prod (above $isDev parameter) and, if isDev is false, you can do it in prod or prod-test<br>";
	$bxData->publishChoices($isTest);
	
	/**
	* Prepare corpus index
	*/
	$logs[] = "force the preparation of a corpus index based on all the terms of the last data you sent ==> you need to have published your data before and you will need to publish them again that the corpus is sent to the index<br>";
	$bxData->prepareCorpusIndex();
	
	/**
	* Prepare autocomplete index
	*/
	//NOT YET READY NOTICE: prepareAutocompleteIndex doesn't add the fields yet even if you pass them to the function like in this example here (TODO), for now, you need to go in the data intelligence admin and set the fields manually. You can contact support@boxalino.com to do that.
	//the autocomplete index is automatically filled with common searches done over time, but of course, before going live, you will not have any. While it is possible to load pre-existing search logs (contact support@boxalino.com to learn how, you can also define some fields which will be considered for the autocompletion anyway (e.g.: brand, product line, etc.).
	$fields = array("products_color");
	$logs[] = "force the preparation of an autocompletion index based on all the terms of the last data you sent ==> you need to have published your data before and you will need to publish them again that the corpus is sent to the index<br>";
	$bxData->prepareAutocompleteIndex($fields);
	
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
