<?php
use PHPUnit\Framework\TestCase;

class RecommendationsSimilarComplementaryTest extends TestCase{

    private $account = "boxalino_automated_tests2";
    private $password = "boxalino_automated_tests2";

    public function test_frontend_recommendations_similar_complementary(){
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

            $complementaryIds = range(11, 20);
            $similarIds = range(1, 10);
            include("../examples/frontend_recommendations_similar_complementary.php");
            $this->assertEquals($exception, null);
            $this->assertEquals($bxResponse->getHitIds($choiceIdSimilar), $similarIds);
            $this->assertEquals($bxResponse->getHitIds($choiceIdComplementary), $complementaryIds);
        }
    }
}