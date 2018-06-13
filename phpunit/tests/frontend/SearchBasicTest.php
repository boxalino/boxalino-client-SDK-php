<?php
use PHPUnit\Framework\TestCase;

class SearchBasicTest extends TestCase
{
	private $account = "boxalino_automated_tests2";
	private $password = "boxalino_automated_tests2";

	public function test_frontend_search_basic(){
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
			$hitIds = array(41, 1940, 1065, 1151, 1241, 1321, 1385, 1401, 1609, 1801);

			//testing the result of the frontend search basic case
			$queryText = "women";
			include("../examples/frontend_search_basic.php");
			$this->assertEquals($exception, null);
			$this->assertEquals($bxResponse->getHitIds(), $hitIds);
			
			//testing the result of the frontend search basic case with semantic filtering blue => products_color=Blue
			$queryText = "blue";
			include("../examples/frontend_search_basic.php");
			$this->assertEquals($exception, null);
			$this->assertEquals($bxResponse->getTotalHitCount(), 79.0);
			
			//testing the result of the frontend search basic case with semantic filtering forcing zero results pink => products_color=Pink
			$queryText = "pink";
			include("../examples/frontend_search_basic.php");
			$this->assertEquals($exception, null);
			$this->assertEquals($bxResponse->getTotalHitCount(), 8.0);

			//testing the result of the frontend search basic case with semantic filtering setting a filter on a specific product only if the search shows zero results (this one should not do it because workout shows results)
			$queryText = "workout";
			include("../examples/frontend_search_basic.php");
			$this->assertEquals($exception, null);
			$this->assertEquals($bxResponse->getTotalHitCount(), 28);

			//testing the result of the frontend search basic case with semantic filtering setting a filter on a specific product only if the search shows zero results (this one should do it because workoutoup shows no results)
			$queryText = "workoutoup";
			include("../examples/frontend_search_basic.php");
			$this->assertEquals($exception, null);
			$this->assertEquals($bxResponse->getTotalHitCount(), 0.0);
		}
	}
}
