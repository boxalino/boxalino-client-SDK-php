<?php
use PHPUnit\Framework\TestCase;

class SearchSubPhrasesTest extends TestCase{

    private $account = "boxalino_automated_tests2";
    private $password = "boxalino_automated_tests2";

    public function test_frontend_search_sub_phrases(){
        global $argv;
        $hosts = ['cdn.bx-cloud.com', 'api.bx-cloud.com'];
        $bxHosts = (isset($argv[2]) ? ($argv[2] == 'all' ? $hosts : array($argv[2])) : $hosts);
        $timeout = isset($argv[3]) ? $argv[3] : 2000;
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