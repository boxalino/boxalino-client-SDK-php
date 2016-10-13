<?php
use PHPUnit\Framework\TestCase;

class ExamplesTests extends TestCase
{
	private $account = "boxalino_automated_tests";
	private $password = "boxalino_automated_tests";

	public function testfrontend_search_basic()
	{
		$account = $this->account;
		$password = $this->password;
		$exception = null;

		//loading the data
		include("../examples/backend_data_basic.php");
		$this->assertEquals($exception, null);

		//testing the result of the frontend search basic case
		include("../examples/frontend_search_basic.php");
		$this->assertEquals($exception, null);
		$this->assertEquals(sizeof($bxResponse->getHitIds()), 2);

	}
}
