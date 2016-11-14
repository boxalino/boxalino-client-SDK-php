<?php
use PHPUnit\Framework\TestCase;

class SearchFacetCategoryTest extends TestCase{

    private $account = "boxalino_automated_tests";
    private $password = "boxalino_automated_tests";

    public function test_frontend_search_facet_category(){
        global $argv;
        $bxHosts = isset($argv[2]) ? array($argv[2]) : ['cdn.bx-cloud.com', 'api.bx-cloud.com'];
        foreach ($bxHosts as $bxHost) {
            $account = $this->account;
            $password = $this->password;
            $host = $bxHost;
            $print = false;
            $exception = null;

            include("../examples/frontend_search_facet_category.php");
            $this->assertEquals($exception, null);
            $this->assertEquals($bxResponse->getHitIds(), array(41, 1940, 1065, 1151, 1241, 1321, 1385, 1401, 1609, 1801));
        }
    }
}