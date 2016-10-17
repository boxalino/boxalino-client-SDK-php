<?php
use PHPUnit\Framework\TestCase;

class SearchAutocompleteItemsTest extends TestCase{

    private $account = "boxalino_automated_tests";
    private $password = "boxalino_automated_tests";

    public function test_frontend_search_autocomplete_items(){
        $account = $this->account;
        $password = $this->password;
        $print = false;
        $exception = null;
        $textualSuggestions = array('ida workout parachute pant','jade yoga jacket','push it messenger bag');

        include("../examples/frontend_search_autocomplete_items.php");
        $this->assertEquals($exception, null);
        $itemSuggestions = $bxAutocompleteResponse->getBxSearchResponse()->getHitFieldValues($fieldNames);
        $this->assertEquals(sizeof($itemSuggestions), 2);
        $this->assertEquals($bxAutocompleteResponse->getTextualSuggestions(), $textualSuggestions);
    }
}