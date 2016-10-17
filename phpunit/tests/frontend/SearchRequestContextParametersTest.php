<?php
use PHPUnit\Framework\TestCase;

class SearchRequestContextParametersTest extends TestCase{

    private $account = "boxalino_automated_tests";
    private $password = "boxalino_automated_tests";

    public function test_frontend_search_request_context_parameters(){
        $account = $this->account;
        $password = $this->password;
        $print = false;
        $exception = null;

        include("../examples/frontend_search_request_context_parameters.php");
        $this->assertEquals($exception, null);
    }
}