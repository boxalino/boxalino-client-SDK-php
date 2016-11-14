<?php
use PHPUnit\Framework\TestCase;

class SearchAutocompletePropertyTest extends TestCase{

    private $account = "boxalino_automated_tests";
    private $password = "boxalino_automated_tests";

    public function test_frontend_search_autocomplete_property(){
        global $argv;
        $bxHosts = isset($argv[2]) ? array($argv[2]) : ['cdn.bx-cloud.com', 'api.bx-cloud.com'];
        foreach ($bxHosts as $bxHost) {
            $account = $this->account;
            $password = $this->password;
            $host = $bxHost;
            $print = false;
            $exception = null;

            include("../examples/frontend_search_autocomplete_property.php");
            $propertyHitValues = $bxAutocompleteResponse->getPropertyHitValues($property);
            $this->assertEquals($exception, null);
            $this->assertEquals(sizeof($propertyHitValues), 2);
            $this->assertEquals($propertyHitValues[0], 'Hoodies &amp; Sweatshirts');
            $this->assertEquals($propertyHitValues[1], 'Bras &amp; Tanks');
        }
    }
}