<?php

App::uses('HashMonad', 'Monaca.Lib');
App::uses('Maybe', 'Monaca.Lib');

class HashMonadTest extends CakeTestCase {

	public function testUnitFailure() {
		$this->setExpectedException('InvalidArgumentException');
		$monad = new HashMonad(123);
	}

	public function testBindEmpty() {
		$monad = new HashMonad(array());
		$called = 0;
		$monad->bind(function($value) use(&$called) {
			$called++;
			return $value;
		});
		$this->assertEquals(0, $called);
	}

	public function testBindNotEmpty() {
		$hash = array(
			'foo' => 1,
			'bar' => 2,
			'baz' => 3
		);
		$monad = new HashMonad($hash);
		$called = 0;
		$new = $monad->bind(function($value) use(&$called) {
			$called++;
			return $value;
		});
		$this->assertEquals(3, $called);
		$this->assertEquals($monad, $new);
		$this->assertEquals($hash, $new->extract());
	}

	public function testComposedHashMonad() {
		$hash = array(
			'foo' => 1,
			'bar' => 2,
			'baz' => 3,
			'qux' => null,
			'foobar' => 4,
		);
		$monad = new HashMonad($hash);
		$newMonad = $monad->bind(function($v) {
			return new Maybe($v);
		});
		$doubled = $newMonad->bind(function($v) {
			return $v * 2;
		});

		$expected = array(
			'foo' => 2,
			'bar' => 4,
			'baz' => 6,
			'qux' => null,
			'foobar' => 8,
		);
		$this->assertEquals($expected, $doubled->extract());
	}

	public function testGetFunctions() {
		$hash = array(
			'foo' => 1,
			'bar' => 2,
			'baz' => 3,
			'qux' => null,
			'foobar' => 4,
		);
		$monad = new HashMonad($hash);

		$this->assertEquals(new Maybe(1), $monad->get('foo'));
		$this->assertEquals(new Maybe(null), $monad->get('some_invalid_key'));
		$this->assertEquals(new Maybe(null), $monad->get('qux'));

		$this->assertEquals(new Maybe('default'), $monad->getOrElse('some_invalid_key', 'default'));

		$called = false;
		$val = $monad->getOrCall('bar', function() use(&$called) {
			$called = true;
			return 1;
		});
		$this->assertFalse($called);
		$this->assertEquals(new Maybe($hash['bar']), $val);

		$val = $monad->getOrCall('invalid_key', function() use(&$called) {
			$called = true;
			return 1;
		});
		$this->assertTrue($called);
		$this->assertEquals(new Maybe(1), $val);

		try {
			$val = $monad->getOrThrow('bar', new Exception('test'));
			$this->assertEquals(new Maybe($hash['bar']), $val);
		} catch (\Exception $e) {
			$this->fail('Exception raised');
		}

		try {
			$val = $monad->getOrThrow('invalid_key', new Exception('test'));
			$this->fail('No exception raised');
		} catch (\Exception $e) {
			$this->assertEquals('test', $e->getMessage());
		}
	}
}
