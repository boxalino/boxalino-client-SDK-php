<?php
use PHPUnit\Framework\TestCase;

class SearchSubPhrasesTest extends TestCase{

    private $account = "boxalino_automated_tests";
    private $password = "boxalino_automated_tests";

    public function test_frontend_search_sub_phrases(){
        global $argv;
        $bxHosts = isset($argv[2]) ? array($argv[2]) : ['cdn.bx-cloud.com', 'api.bx-cloud.com'];
        foreach ($bxHosts as $bxHost) {
            $account = $this->account;
            $password = $this->password;
            $host = $bxHost;
            $print = false;
            $exception = null;

            include("../examples/frontend_search_sub_phrases.php");
            $this->assertEquals($exception, null);
            $this->assertTrue($bxResponse->areThereSubPhrases());
            $this->assertEquals(sizeof($bxResponse->getSubPhrasesQueries()), 2);
        }
    }
}