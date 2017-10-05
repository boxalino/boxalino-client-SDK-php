<?php

/**
 * In this example, we take a very simple XML file with product and categories data (and the link between them), generate the specifications, load them, publish them and push the data to Boxalino Data Intelligence
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

    $mainProductFile = '../sample_data/products.xml'; //xml file of all the products
    $itemIdColumn = 'id'; //the element of the xml with the unique id of each item
    $productsXPath = '/products/product'; //path from the root to the products

    $categoryFile = '../sample_data/categories.xml'; //xml file of all the categories
    $categoryIdColumn = 'category_id'; //the element of the xml with the unique category id
    $parentCategoryIdColumn = 'parent_id'; //the element of the xml with the parent category id
    $categoryLabelColumns = array("en"=>"name/translation[@locale='en']"); //the element of the xml with the category label in each language
    $categoriesXPath = '/categories/category'; //path from the root to the categories

    $productCategoryFile = '../sample_data/product_categories.xml'; //xml file of all the product to category mapping
    $productCategoryXPath = '/category_products/category_product'; //path from the root to the product category mapping

    //add a xml file as main product file
    $sourceKey = $bxData->addMainXMLItemFile($mainProductFile, $itemIdColumn, $productsXPath);

    //add a xml file with products ids to categories ids
    $productToCategoriesSourceKey = $bxData->addXMLItemFile($productCategoryFile, $itemIdColumn, $productCategoryXPath);

    //add a xml file with categories
    $bxData->addXMLCategoryFile($categoryFile, $categoryIdColumn, $parentCategoryIdColumn, $categoryLabelColumns, $categoriesXPath);

    //this part is only necessary to do when you push your data in full, as no specifications changes should not be published without a full data sync following next
    //even when you publish your data in full, you don't need to repush your data specifications if you know they didn't change, however, it is totally fine (and suggested) to push them everytime if you are not sure if something changed or not
    if(!$isDelta) {

        //set the category field from the source mapping products to category ids (indicating which column of that file contains the category_id)
        $bxData->setCategoryField($productToCategoriesSourceKey, $categoryIdColumn);

        $logs[] = "publish the data specifications";
        $bxData->pushDataSpecifications();

        $logs[] = "publish the api owner changes"; //if the specifications have changed since the last time they were pushed
        $bxData->publishChanges();
    }

    $logs[] = "push the data for data sync";
    $bxData->pushData();
    if(!isset($print) || $print) {
        echo implode("<br/>", $logs);
    }

} catch(\Exception $e) {

    //be careful not to print the error message on your publish web-site as sensitive information like credentials might be indicated for debug purposes
    $exception = $e->getMessage();
    if(!isset($print) || $print) {
        echo $exception;
    }
}
