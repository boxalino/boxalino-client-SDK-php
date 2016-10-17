<?php
use PHPUnit\Framework\TestCase;

class SearchAutocompletePropertyTest extends TestCase{

    private $account = "boxalino_automated_tests";
    private $password = "boxalino_automated_tests";

    public function test_frontend_search_autocomplete_property(){
        $account = $this->account;
        $password = $this->password;
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