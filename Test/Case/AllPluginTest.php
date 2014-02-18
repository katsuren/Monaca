<?php

namespace Monaca;

class AllPluginTest extends \PHPUnit_Framework_TestSuite {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new \CakeTestSuite('All Monaca Plugin tests');

		$path = APP . 'Plugin' . DS . 'Monaca' . DS . 'Test' . DS . 'Case' . DS;
		$suite->addTestFile($path . 'AllComponentTest.php');
		$suite->addTestFile($path . 'AllControllerTest.php');
		$suite->addTestFile($path . 'AllLibTest.php');

		return $suite;
	}
}

