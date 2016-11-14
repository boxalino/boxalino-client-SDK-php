<?php
use PHPUnit\Framework\TestCase;

class SearchSortField extends TestCase{

    private $account = "boxalino_automated_tests";
    private $password = "boxalino_automated_tests";

    public function test_frontend_search_sort_field(){
        global $argv;
        $bxHosts = isset($argv[2]) ? array($argv[2]) : ['cdn.bx-cloud.com', 'api.bx-cloud.com'];
        foreach ($bxHosts as $bxHost) {
            $account = $this->account;
            $password = $this->password;
            $host = $bxHost;
            $print = false;
            $exception = null;

            include("../examples/frontend_search_sort_field.php");
            $this->assertEquals($exception, null);
            $this->assertEquals(array_keys($bxResponse->getHitFieldValues(array($sortField))), array(1940, 41));
        }
    }
}