<?php
use PHPUnit\Framework\TestCase;

class SearchDebugRequestTest extends TestCase{

    private $account = "boxalino_automated_tests";
    private $password = "boxalino_automated_tests";

    public function test_frontend_search_debug_request(){
        global $argv;
        $bxHosts = isset($argv[2]) ? array($argv[2]) : ['cdn.bx-cloud.com', 'api.bx-cloud.com'];
        foreach ($bxHosts as $bxHost) {
            $account = $this->account;
            $password = $this->password;
            $host = $bxHost;
            $print = false;
            $exception = null;

            include("../examples/frontend_search_debug_request.php");
            $this->assertEquals($exception, null);
            $this->assertTrue($bxClient->getThriftChoiceRequest() instanceof \com\boxalino\p13n\api\thrift\ChoiceRequest);
        }
    }
}