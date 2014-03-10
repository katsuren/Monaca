<?php

App::uses('Monaca', 'Monaca.Lib');

class MonacaTest extends CakeTestCase {

	public function testGetConfigureFunctions() {
		Configure::write('key', 100);
		Configure::write('foo', array('bar' => 'baz'));

		$this->assertEquals(100, Monaca::getConfig('key'));
		$this->assertEquals('baz', Monaca::getConfig('foo.bar'));
		$this->assertEquals(null, Monaca::getConfig('invalid'));

		$this->assertEquals('default', Monaca::getConfigOrElse('invalid', 'default'));

		$called = false;
		$val = Monaca::getConfigOrCall('key', function() use(&$called) {
			$called = true;
			return 'value';
		});
		$this->assertFalse($called);
		$this->assertEquals(100, $val);

		$val = Monaca::getConfigOrCall('invalid', function() use(&$called) {
			$called = true;
			return 'value';
		});
		$this->assertTrue($called);
		$this->assertEquals('value', $val);

		$ex = new \Exception('test');
		$val = Monaca::getConfigOrThrow('key', $ex);
		$this->assertEquals(100, $val);

		try {
			$val = Monaca::getConfigOrThrow('invalid', $ex);
			$this->fail('No Exception raised');
		} catch (\Exception $e) {
			$this->assertEquals('test', $e->getMessage());
		}
	}

/**
 * If you need testing this function, you should include --stderr flag.
 * more detail,
 * see http://book.cakephp.org/2.0/en/development/testing.html#running-tests-that-use-sessions
 *
 * @group session
 */
	public function testGetSessionFunctions() {
		CakeSession::write('key', 100);
		CakeSession::write('foo', array('bar' => 'baz'));

		$this->assertEquals(100, Monaca::getSession('key'));
		$this->assertEquals('baz', Monaca::getSession('foo.bar'));
		$this->assertEquals(null, Monaca::getSession('invalid'));

		$this->assertEquals('default', Monaca::getSessionOrElse('invalid', 'default'));

		$called = false;
		$val = Monaca::getSessionOrCall('key', function() use(&$called) {
			$called = true;
			return 'value';
		});
		$this->assertFalse($called);
		$this->assertEquals(100, $val);

		$val = Monaca::getSessionOrCall('invalid', function() use(&$called) {
			$called = true;
			return 'value';
		});
		$this->assertTrue($called);
		$this->assertEquals('value', $val);

		$ex = new \Exception('test');
		$val = Monaca::getSessionOrThrow('key', $ex);
		$this->assertEquals(100, $val);

		try {
			$val = Monaca::getSessionOrThrow('invalid', $ex);
			$this->fail('No Exception raised');
		} catch (\Exception $e) {
			$this->assertEquals('test', $e->getMessage());
		}
	}
}
