<?php
use PHPUnit\Framework\TestCase;

class RecommendationsBasketTest extends TestCase{

    private $account = "boxalino_automated_tests";
    private $password = "boxalino_automated_tests";

    public function test_frontend_recommendations_basket(){
        $account = $this->account;
        $password = $this->password;
        $print = false;
        $exception = null;

        $hitIds = range(1,10);
        include("../examples/frontend_recommendations_basket.php");
        $this->assertEquals($exception, null);
        $this->assertEquals($bxResponse->getHitIds(), $hitIds);
    }
}