<?php
use PHPUnit\Framework\TestCase;

class SearchSubPhrasesTest extends TestCase{

    private $account = "boxalino_automated_tests";
    private $password = "boxalino_automated_tests";

    public function test_frontend_search_sub_phrases(){
        $account = $this->account;
        $password = $this->password;
        $print = false;
        $exception = null;

        include("../examples/frontend_search_sub_phrases.php");
        $this->assertEquals($exception, null);
        $this->assertTrue($bxResponse->areThereSubPhrases());
        $this->assertEquals(sizeof($bxResponse->getSubPhrasesQueries()), 2);
    }
}