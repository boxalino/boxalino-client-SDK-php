<?php
use PHPUnit\Framework\TestCase;

class SearchFacetTest extends TestCase{

    private $account = "boxalino_automated_tests";
    private $password = "boxalino_automated_tests";

    public function test_frontend_search_facet(){
        global $argv;
        $bxHosts = isset($argv[2]) ? array($argv[2]) : ['cdn.bx-cloud.com', 'api.bx-cloud.com'];
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