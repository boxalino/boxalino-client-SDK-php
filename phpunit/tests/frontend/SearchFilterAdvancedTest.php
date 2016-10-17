<?php
use PHPUnit\Framework\TestCase;

class SearchFilterAdvancedTest extends TestCase{

    private $account = "boxalino_automated_tests";
    private $password = "boxalino_automated_tests";

    public function test_frontend_search_filter_advanced(){
        $account = $this->account;
        $password = $this->password;
        $print = false;
        $exception = null;
        
        include("../examples/frontend_search_filter_advanced.php");
        $this->assertEquals($exception, null);
        $this->assertEquals(sizeof($bxResponse->getHitFieldValues($fieldNames)), 1);
    }
}