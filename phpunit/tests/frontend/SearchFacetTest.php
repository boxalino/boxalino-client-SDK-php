<?php
use PHPUnit\Framework\TestCase;

class SearchFacetTest extends TestCase{

    private $account = "boxalino_automated_tests2";
    private $password = "boxalino_automated_tests2";

    public function test_frontend_search_facet(){
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

            //testing the result of the frontend search facet case
            include("../examples/frontend_search_facet.php");
            $this->assertEquals($exception, null);
            $this->assertEquals($bxResponse->getHitFieldValues(array($facetField))[41], array('products_color' => array('Black', 'Gray', 'Yellow')));
            $this->assertEquals($bxResponse->getHitFieldValues(array($facetField))[1940], array('products_color' => array('Gray', 'Orange', 'Yellow')));
        }
    }
}