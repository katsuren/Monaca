<?php

namespace Monaca;

\App::uses('HashMonad', 'Monaca.Lib');
\App::uses('Maybe', 'Monaca.Lib');

class OptionTest extends \CakeTestCase {

	public function testOptionalGetFunctions() {
		$just = new \Maybe('foo');
		$nothing = new \Maybe(null);

		$this->assertEquals('foo', $just->getOrElse(null, 'bar'));
		$this->assertEquals('bar', $nothing->getOrElse(null, 'bar'));

		$called = false;
		$val = $just->getOrCall(null, function() use(&$called) {
			$called = true;
			return 'called';
		});
		$this->assertFalse($called);
		$this->assertEquals('foo', $val);

		$val = $nothing->getOrCall(null, function() use(&$called) {
			$called = true;
			return 'called';
		});
		$this->assertTrue($called);
		$this->assertEquals('called', $val);

		$ex = new \Exception('test');
		$val = $just->getOrThrow(null, $ex);
		$this->assertEquals('foo', $val);

		try {
			$val = $nothing->getOrThrow(null, $ex);
			$this->fail('No exception raised.');
		} catch (\Exception $e) {
			$this->assertEquals('test', $e->getMessage());
		}
	}

	public function testOptionalGetHashFunctions() {
		$just = new \Maybe(array(
			'hoge' => 'fuga',
			'foo' => array(
				'bar' => 'buz'
			)
		));

		$this->assertEquals('fuga', $just->getOrElse('hoge', 'bar'));
		$this->assertEquals('buz', $just->getOrElse('foo.bar', 'bar'));
	}
}
