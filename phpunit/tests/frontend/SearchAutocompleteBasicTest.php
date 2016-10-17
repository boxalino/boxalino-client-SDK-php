<?php
use PHPUnit\Framework\TestCase;

class SearchAutocompleteBasicTest extends TestCase{

    private $account = "boxalino_automated_tests";
    private $password = "boxalino_automated_tests";

    public function test_frontend_search_autocomplete_basic(){
        $account = $this->account;
        $password = $this->password;
        $print = false;
        $exception = null;
        $textualSuggestions = array('ida workout parachute pant','jade yoga jacket','push it messenger bag');

        include("../examples/frontend_search_autocomplete_basic.php");
        $this->assertEquals($exception, null);
        $this->assertEquals($bxAutocompleteResponse->getTextualSuggestions(), $textualSuggestions);
    }
}