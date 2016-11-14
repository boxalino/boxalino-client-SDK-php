<?php
use PHPUnit\Framework\TestCase;

class Search2ndPageTest extends TestCase{

    private $account = "boxalino_automated_tests";
    private $password = "boxalino_automated_tests";

    public function test_frontend_search_2nd_page(){
        global $argv;
        $bxHosts = isset($argv[2]) ? array($argv[2]) : ['cdn.bx-cloud.com', 'api.bx-cloud.com'];
        foreach ($bxHosts as $bxHost) {
            $account = $this->account;
            $password = $this->password;
            $host = $bxHost;
            $print = false;
            $exception = null;

            $hitIds = array(40, 41, 42, 44);
            include("../examples/frontend_search_2nd_page.php");
            $this->assertEquals($exception, null);
            $this->assertEquals($bxResponse->getHitIds(), $hitIds);
        }
    }
}