<?php

/**
 * In this example, we show how a full export would look like.
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

    //Product export
    $file = '../sample_data/products.csv'; //a csv file with header row
    $itemIdColumn = 'id'; //the column header row name of the csv with the unique id of each item
    $colorFile = '../sample_data/color.csv'; //a csv file with header row
    $colorIdColumn = 'color_id'; //column header row name of the csv with the unique category id
    $colorLabelColumns = array('en'=>'value_en'); //column header row names of the csv with the category label in each language
    $productToColorsFile = '../sample_data/product_color.csv'; //a csv file with header row

    //add a csv file as main product file
    $sourceKey = $bxData->addMainCSVItemFile($file, $itemIdColumn);
    $bxData->addSourceStringField($sourceKey, "related_product_ids", "related_product_ids");
    $bxData->addFieldParameter($sourceKey, "related_product_ids", "splitValues", ",");

    //declare the fields
    $bxData->addSourceTitleField($sourceKey, array("en"=>"name_en"));
    $bxData->addSourceDescriptionField($sourceKey, array("en"=>"description_en"));
    $bxData->addSourceListPriceField($sourceKey, "list_price");
    $bxData->addSourceDiscountedPriceField($sourceKey, "discounted_price");
    $bxData->addSourceLocalizedTextField($sourceKey, "short_description", array("en"=>"short_description_en"));
    $bxData->addSourceStringField($sourceKey, "sku", "sku");

    //add a csv file with products ids to Colors ids
    $productToColorsSourceKey = $bxData->addCSVItemFile($productToColorsFile, $itemIdColumn);

    //add a csv file with Colors
    $colorSourceKey = $bxData->addResourceFile($colorFile, $colorIdColumn, $colorLabelColumns);
    $bxData->addSourceLocalizedTextField($productToColorsSourceKey, "color", $colorIdColumn, $colorSourceKey);


    //Category export
    $categoryFile = '../sample_data/categories.csv'; //a csv file with header row
    $categoryIdColumn = 'category_id'; //column header row name of the csv with the unique category id
    $parentCategoryIdColumn = 'parent_id'; //column header row name of the csv with the parent category id
    $categoryLabelColumns = array('en'=>'value_en'); //column header row names of the csv with the category label in each language
    $productToCategoriesFile = '../sample_data/product_categories.csv'; //a csv file with header row

    //add a csv file with products ids to categories ids
    $productToCategoriesSourceKey = $bxData->addCSVItemFile($productToCategoriesFile, $itemIdColumn);

    //add a csv file with categories
    $bxData->addCategoryFile($categoryFile, $categoryIdColumn, $parentCategoryIdColumn, $categoryLabelColumns);
    $bxData->setCategoryField($productToCategoriesSourceKey, $categoryIdColumn);

    //Customer export
    $customerFile = '../sample_data/customers.csv'; //a csv file with header row
    $customerIdColumn = 'customer_id'; //the column header row name of the csv with the unique id of each item

    //add a csv file as main customer file
    $customerSourceKey = $bxData->addMainCSVCustomerFile($customerFile, $customerIdColumn);
    $bxData->addSourceStringField($customerSourceKey, "country", "country");
    $bxData->addSourceStringField($customerSourceKey, "zip", "zip");

   //Transaction export
    $transactionFile = '../sample_data/transactions.csv'; //a csv file with header row, this file should contain one entry per product and per transaction (so the same transaction should appear several time if it contains more than 1 product
    $orderIdColumn = 'order_id'; //the column header row name of the csv with the order (or transaction) id
    $transactionProductIdColumn = 'product_id'; //the column header row name of the csv with the product id
    $transactionCustomerIdColumn = 'customer_id'; //the column header row name of the csv with the customer id
    $orderDateIdColumn = 'order_date'; //the column header row name of the csv with the order date
    $totalOrderValueColumn = 'total_order_value'; //the column header row name of the csv with the total order value
    $productListPriceColumn = 'price'; //the column header row name of the csv with the product list price
    $productDiscountedPriceColumn = 'discounted_price'; //the column header row name of the csv with the product price after discounts (real price paid)

    //optional fields, provided here with default values (so, no effect if not provided), matches the field to connect to the transaction product id and customer id columns (if the ids are not the same as the itemIdColumn of your products and customers files, then you can define another field)
    $transactionProductIdField = 'bx_item_id'; //default value (can be left null) to define a specific field to map with the product id column
    $transactionCustomerIdField = 'bx_customer_id'; //default value (can be left null) to define a specific field to map with the product id column

    //add a csv file as main customer file
    $bxData->setCSVTransactionFile($transactionFile, $orderIdColumn, $transactionProductIdColumn, $transactionCustomerIdColumn, $orderDateIdColumn, $totalOrderValueColumn, $productListPriceColumn, $productDiscountedPriceColumn, $transactionProductIdField, $transactionCustomerIdField);

    //prepare autocomplete index
    $bxData->prepareCorpusIndex();
    $fields = array("products_color");
    $bxData->prepareAutocompleteIndex($fields);

    $logs[] = "publish the data specifications";
    $bxData->pushDataSpecifications();

    $logs[] = "publish the api owner changes"; //if the specifications have changed since the last time they were pushed
    $bxData->publishChanges();

    $logs[] = "push the data for data sync";
    $bxData->pushData();
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
