<?php

namespace Monaca;

class AllViewTest extends \PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite = new \CakeTestSuite('All View class in Monaca Plugin tests');
		$suite->addTestDirectory(APP . 'Plugin' . DS . 'Monaca' . DS . 'Test' . DS . 'Case' . DS . 'View');
		return $suite;
	}
}
