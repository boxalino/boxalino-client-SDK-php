<?php
use PHPUnit\Framework\TestCase;

class SearchSortField extends TestCase{

    private $account = "boxalino_automated_tests";
    private $password = "boxalino_automated_tests";

    public function test_frontend_search_sort_field(){
        $account = $this->account;
        $password = $this->password;
        $print = false;
        $exception = null;

        include("../examples/frontend_search_sort_field.php");
        $this->assertEquals($exception, null);
        $this->assertEquals(array_keys($bxResponse->getHitFieldValues(array($sortField))), array(1940,41));

    }
}