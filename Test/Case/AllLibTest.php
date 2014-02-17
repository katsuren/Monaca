<?php

class AllLibTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite = new CakeTestSuite('All Lib class in Monaca Plugin tests');
		$suite->addTestDirectory(APP . 'Plugin' . DS . 'Monaca' . DS . 'Test' . DS . 'Case' . DS . 'Lib');
		return $suite;
	}
}
