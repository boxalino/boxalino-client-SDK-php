<?php
use PHPUnit\Framework\TestCase;

class SearchBasicTest extends TestCase
{
	private $account = "boxalino_automated_tests";
	private $password = "boxalino_automated_tests";

	public function test_frontend_search_basic()
	{
		$account = $this->account;
		$password = $this->password;
		$print = false;
		$exception = null;
		$hitIds = array(41,1940,1065,1151,1241,1321,1385,1401,1497,1609);

		//testing the result of the frontend search basic case
		include("../examples/frontend_search_basic.php");
		$this->assertEquals($exception, null);
		$this->assertEquals($bxResponse->getHitIds(), $hitIds);

	}
}
