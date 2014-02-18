<?php

App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('MonadController', 'Monaca.Controller');
App::uses('MonadView', 'Monaca.View');

class MonadViewTest extends CakeTestCase {

	public function testGetFunctions() {
		$controller = new MonadController(new CakeRequest(), new CakeResponse());
		$controller->viewVars = array(
			'hoge' => 'fuga',
			'foo' => array(
				'bar' => 'buz'
			)
		);
		$sut = new MonadView($controller);

		$this->assertEquals('buz', $sut->getOrElse('foo.bar', null));
		$this->assertEquals('default', $sut->getOrElse('invalid_key', 'default'));

		$called = false;
		$val = $sut->getOrCall('hoge', function() use(&$called) {
			$called = true;
			return 'value';
		});
		$this->assertFalse($called);
		$this->assertEquals('fuga', $val);

		$val = $sut->getOrCall('invalid', function() use(&$called) {
			$called = true;
			return 'value';
		});
		$this->assertTrue($called);
		$this->assertEquals('value', $val);

		$ex = new \Exception('test');
		$val = $sut->getOrThrow('hoge', $ex);
		$this->assertEquals('fuga', $val);

		try {
			$val = $sut->getOrThrow('invalid', $ex);
			$this->fail('No exception raised');
		} catch (\Exception $e) {
			$this->assertEquals('test', $e->getMessage());
		}
	}
}
