<?php

namespace Monaca;

class AllComponentTest extends \PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite = new \CakeTestSuite('All Component class in Monaca Plugin tests');
		$suite->addTestDirectory(APP . 'Plugin' . DS . 'Monaca' . DS . 'Test' . DS . 'Case' . DS . 'Controller' . DS . 'Component');
		return $suite;
	}
}


