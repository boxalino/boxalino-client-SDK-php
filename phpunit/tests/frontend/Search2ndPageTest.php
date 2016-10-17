<?php
use PHPUnit\Framework\TestCase;

class Search2ndPageTest extends TestCase{

    private $account = "boxalino_automated_tests";
    private $password = "boxalino_automated_tests";

    public function test_frontend_search_2nd_page(){
        $account = $this->account;
        $password = $this->password;
        $print = false;
        $exception = null;

        $hitIds = array(40,41,42,44);
        include("../examples/frontend_search_2nd_page.php");
        $this->assertEquals($exception, null);
        $this->assertEquals($bxResponse->getHitIds(), $hitIds);
    }
}