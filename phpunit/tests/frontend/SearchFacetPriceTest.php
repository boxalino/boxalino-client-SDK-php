<?php
use PHPUnit\Framework\TestCase;

class SearchFacetPriceTest extends TestCase{

    private $account = "boxalino_automated_tests";
    private $password = "boxalino_automated_tests";

    public function test_frontend_search_facet_price(){
        global $argv;
        $bxHosts = isset($argv[2]) ? array($argv[2]) : ['cdn.bx-cloud.com', 'api.bx-cloud.com'];
        foreach ($bxHosts as $bxHost) {
            $account = $this->account;
            $password = $this->password;
            $host = $bxHost;
            $print = false;
            $exception = null;

            include("../examples/frontend_search_facet_price.php");
            $this->assertEquals($exception, null);
            $this->assertEquals($facets->getPriceRanges()[0], "22-84");
            foreach ($bxResponse->getHitFieldValues(array($facets->getPriceFieldName())) as $fieldValueMap) {
                $this->assertGreaterThan((float)$fieldValueMap['discountedPrice'][0], 84.0);
                $this->assertLessThanOrEqual((float)$fieldValueMap['discountedPrice'][0], 22.0);

            }
        }
    }
}