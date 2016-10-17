<?php
use PHPUnit\Framework\TestCase;

class SearchFilterTest extends TestCase{

    private $account = "boxalino_automated_tests";
    private $password = "boxalino_automated_tests";

    public function test_frontend_search_filter(){
        $account = $this->account;
        $password = $this->password;
        $print = false;
        $exception = null;

        include("../examples/frontend_search_filter.php");
        $this->assertEquals($exception, null);
        $this->assertTrue(!in_array("41",$bxResponse->getHitIds()));
        $this->assertTrue(!in_array("1940",$bxResponse->getHitIds()));
    }
}