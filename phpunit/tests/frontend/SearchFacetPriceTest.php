<?php
use PHPUnit\Framework\TestCase;

class SearchFacetPriceTest extends TestCase{

    private $account = "boxalino_automated_tests";
    private $password = "boxalino_automated_tests";

    public function test_frontend_search_facet_price(){
        $account = $this->account;
        $password = $this->password;
        $print = false;
        $exception = null;

        include("../examples/frontend_search_facet_price.php");
        $this->assertEquals($exception, null);
        $this->assertEquals($facets->getPriceRanges()[0], "22-84");
        foreach($bxResponse->getHitFieldValues(array($facets->getPriceFieldName())) as $fieldValueMap){
            $this->assertGreaterThan((float)$fieldValueMap['discountedPrice'][0], 84.0);
            $this->assertLessThanOrEqual((float)$fieldValueMap['discountedPrice'][0], 22.0);

        }
    }
}