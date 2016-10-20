<?php
use PHPUnit\Framework\TestCase;

class DataFullExportTest extends TestCase{

    private $account = "boxalino_automated_tests";
    private $password = "boxalino_automated_tests";

    public function test_backend_data_full_export(){
        $account = $this->account;
        $password = $this->password;
        $print = false;
        $exception = null;

        include("../examples/backend_data_full_export.php");
        $this->assertEquals($exception, null);
    }
}