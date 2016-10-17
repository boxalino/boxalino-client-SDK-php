<?php
use PHPUnit\Framework\TestCase;

class DataFullExportTest extends TestCase{

    private $account = "boxalino_automated_tests";
    private $password = "boxalino_automated_tests";

    public function test_backend_full_export(){
        $account = $this->account;
        $password = $this->password;
        $print = false;
        $exception = null;

        include("../examples/backend_data_basic.php");
        $this->assertEquals($exception, null);

        include("../examples/backend_data_customers.php");
        $this->assertEquals($exception, null);

        include("../examples/backend_data_debug_xml.php");
        $this->assertEquals($exception, null);

        include("../examples/backend_data_resource.php");
        $this->assertEquals($exception, null);

        include("../examples/backend_data_split_field_values.php");
        $this->assertEquals($exception, null);

        include("../examples/backend_data_transactions.php");
        $this->assertEquals($exception, null);

        include("../examples/backend_data_init.php");
        $this->assertEquals($exception, null);

        include("../examples/backend_data_categories.php");
        $this->assertEquals($exception, null);

    }
}