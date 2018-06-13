<?php
use PHPUnit\Framework\TestCase;

class SearchAutocompleteItemsBundledTest extends TestCase{

    private $account = "boxalino_automated_tests2";
    private $password = "boxalino_automated_tests2";

    public function test_frontend_search_autocomplete_items_bundled(){
        global $argv;
        $hosts = ['cdn.bx-cloud.com', 'api.bx-cloud.com'];
        $bxHosts = (isset($argv[2]) ? ($argv[2] == 'all' ? $hosts : array($argv[2])) : $hosts);
        $timeout = isset($argv[3]) ? $argv[3] : 2000;
        foreach ($bxHosts as $bxHost) {
            $account = $this->account;
            $password = $this->password;
            $host = $bxHost;
            $print = false;
            $exception = null;

            $firstTextualSuggestions = array('ida workout parachute pant', 'jade yoga jacket', 'push it messenger bag');
            $secondTextualSuggestions = array('argus all weather tank', 'jupiter all weather trainer', 'livingston all purpose tight');

            include("../examples/frontend_search_autocomplete_items_bundled.php");
            $this->assertEquals($exception, null);
            $this->assertEquals(sizeof($bxAutocompleteResponses), 2);

            //first response
            $this->assertEquals($bxAutocompleteResponses[0]->getTextualSuggestions(), $firstTextualSuggestions);
            //global ids
            $this->assertEquals(array_keys($bxAutocompleteResponses[0]->getBxSearchResponse()->getHitFieldValues($fieldNames)), array(115, 131, 227, 355, 611));

            //second response
            $this->assertEquals($bxAutocompleteResponses[1]->getTextualSuggestions(), $secondTextualSuggestions);
            //global ids
            $this->assertEquals(array_keys($bxAutocompleteResponses[1]->getBxSearchResponse()->getHitFieldValues($fieldNames)), array(1545));
        }
    }
}