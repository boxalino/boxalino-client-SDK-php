<?php
use PHPUnit\Framework\TestCase;

class SearchFacetPriceTest extends TestCase{

    private $account = "boxalino_automated_tests2";
    private $password = "boxalino_automated_tests2";

    public function test_frontend_search_facet_price(){
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