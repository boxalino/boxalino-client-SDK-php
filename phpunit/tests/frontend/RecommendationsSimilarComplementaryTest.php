<?php
use PHPUnit\Framework\TestCase;

class RecommendationsSimilarComplementaryTest extends TestCase{

    private $account = "boxalino_automated_tests";
    private $password = "boxalino_automated_tests";

    public function test_frontend_recommendations_similar_complementary(){
        $account = $this->account;
        $password = $this->password;
        $print = false;
        $exception = null;

        $complementaryIds = range(11,20);
        $similarIds = range(1,10);
        include("../examples/frontend_recommendations_similar_complementary.php");
        $this->assertEquals($exception, null);
        $this->assertEquals($bxResponse->getHitIds($choiceIdSimilar), $similarIds);
        $this->assertEquals($bxResponse->getHitIds($choiceIdComplementary), $complementaryIds);
    }
}