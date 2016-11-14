<?php
use PHPUnit\Framework\TestCase;

class SearchAutocompleteItemsTest extends TestCase{

    private $account = "boxalino_automated_tests";
    private $password = "boxalino_automated_tests";

    public function test_frontend_search_autocomplete_items(){
        global $argv;
        $bxHosts = isset($argv[2]) ? array($argv[2]) : ['cdn.bx-cloud.com', 'api.bx-cloud.com'];
        foreach ($bxHosts as $bxHost) {
            $account = $this->account;
            $password = $this->password;
            $host = $bxHost;
            $print = false;
            $exception = null;
            $textualSuggestions = array('ida workout parachute pant', 'jade yoga jacket', 'push it messenger bag');

            include("../examples/frontend_search_autocomplete_items.php");
            $this->assertEquals($exception, null);
            $itemSuggestions = $bxAutocompleteResponse->getBxSearchResponse()->getHitFieldValues($fieldNames);
            $this->assertEquals(sizeof($itemSuggestions), 5);
            $this->assertEquals($bxAutocompleteResponse->getTextualSuggestions(), $textualSuggestions);
        }
    }
}