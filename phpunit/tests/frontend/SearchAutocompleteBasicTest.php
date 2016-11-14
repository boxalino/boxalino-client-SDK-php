<?php
use PHPUnit\Framework\TestCase;

class SearchAutocompleteBasicTest extends TestCase{

    private $account = "boxalino_automated_tests";
    private $password = "boxalino_automated_tests";

    public function test_frontend_search_autocomplete_basic(){
        global $argv;
        $bxHosts = isset($argv[2]) ? array($argv[2]) : ['cdn.bx-cloud.com', 'api.bx-cloud.com'];
        foreach ($bxHosts as $bxHost) {
            $account = $this->account;
            $password = $this->password;
            $host = $bxHost;
            $print = false;
            $exception = null;
            $textualSuggestions = array('ida workout parachute pant', 'jade yoga jacket', 'push it messenger bag');

            include("../examples/frontend_search_autocomplete_basic.php");
            $this->assertEquals($exception, null);
            $this->assertEquals($bxAutocompleteResponse->getTextualSuggestions(), $textualSuggestions);
        }
    }
}