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
}
