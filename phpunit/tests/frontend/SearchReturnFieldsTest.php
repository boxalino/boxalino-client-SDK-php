<?php
use PHPUnit\Framework\TestCase;

class SearchReturnFieldsTest extends TestCase{

    private $account = "boxalino_automated_tests";
    private $password = "boxalino_automated_tests";

    public function test_frontend_search_return_fields(){
        $account = $this->account;
        $password = $this->password;
        $print = false;
        $exception = null;
        
        include("../examples/frontend_search_return_fields.php");
        $this->assertEquals($exception, null);
        $this->assertEquals($bxResponse->getHitFieldValues($fieldNames)[41][$fieldNames[0]], array('Black','Gray','Yellow'));
        $this->assertEquals($bxResponse->getHitFieldValues($fieldNames)[1940][$fieldNames[0]], array('Gray','Orange','Yellow'));
    }
}