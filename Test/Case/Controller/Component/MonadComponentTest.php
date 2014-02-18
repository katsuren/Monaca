<?php

App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('MonadController', 'Monaca.Controller');
App::uses('MonadComponent', 'Monaca.Controller/Component');

App::uses('Configure', 'Core');
App::uses('CakeSession', 'Model/Datasource');

class MonadComponentTest extends CakeTestCase {

	//---------------------------
	//
	// Properties
	//
	//---------------------------

	public $component = null;

	public $controller = null;

	//---------------------------
	//
	// Preparation
	//
	//---------------------------

	public function setUp() {
		parent::setUp();

		$collection = new ComponentCollection();
		$this->component = new MonadComponent($collection);
		$request = new CakeRequest();
		$response = new CakeResponse();
		$this->controller = new MonadController($request, $response);
		$this->component->startup($this->controller);
	}

	public function tearDown() {
		parent::tearDown();

		unset($this->component);
		unset($this->controller);
	}

	//---------------------------
	//
	// Tests
	//
	//---------------------------

	public function testGetConfigureFunctions() {
		Configure::write('key', 100);
		Configure::write('foo', array('bar' => 'baz'));

		$sut = $this->component;

		$this->assertEquals(100, $sut->getConfig('key'));
		$this->assertEquals('baz', $sut->getConfig('foo.bar'));
		$this->assertEquals(null, $sut->getConfig('invalid'));

		$this->assertEquals('default', $sut->getConfigOrElse('invalid', 'default'));

		$called = false;
		$val = $sut->getConfigOrCall('key', function() use(&$called) {
			$called = true;
			return 'value';
		});
		$this->assertFalse($called);
		$this->assertEquals(100, $val);

		$val = $sut->getConfigOrCall('invalid', function() use(&$called) {
			$called = true;
			return 'value';
		});
		$this->assertTrue($called);
		$this->assertEquals('value', $val);

		$ex = new \Exception('test');
		$val = $sut->getConfigOrThrow('key', $ex);
		$this->assertEquals(100, $val);

		try {
			$val = $sut->getConfigOrThrow('invalid', $ex);
			$this->fail('No Exception raised');
		} catch (\Exception $e) {
			$this->assertEquals('test', $e->getMessage());
		}
	}

/**
 * If you need testing this function, you should include --stderr flag.
 * more detail,
 * see http://book.cakephp.org/2.0/en/development/testing.html#running-tests-that-use-sessions
 */
	public function testGetSessionFunctions() {
		CakeSession::write('key', 100);
		CakeSession::write('foo', array('bar' => 'baz'));

		$sut = $this->component;

		$this->assertEquals(100, $sut->getSession('key'));
		$this->assertEquals('baz', $sut->getSession('foo.bar'));
		$this->assertEquals(null, $sut->getSession('invalid'));

		$this->assertEquals('default', $sut->getSessionOrElse('invalid', 'default'));

		$called = false;
		$val = $sut->getSessionOrCall('key', function() use(&$called) {
			$called = true;
			return 'value';
		});
		$this->assertFalse($called);
		$this->assertEquals(100, $val);

		$val = $sut->getSessionOrCall('invalid', function() use(&$called) {
			$called = true;
			return 'value';
		});
		$this->assertTrue($called);
		$this->assertEquals('value', $val);

		$ex = new \Exception('test');
		$val = $sut->getSessionOrThrow('key', $ex);
		$this->assertEquals(100, $val);

		try {
			$val = $sut->getSessionOrThrow('invalid', $ex);
			$this->fail('No Exception raised');
		} catch (\Exception $e) {
			$this->assertEquals('test', $e->getMessage());
		}
	}

	public function testGetPostFunctions() {
		$restore = $_POST;
		$_POST = array(
			'key' => 100,
			'foo' => array(
				'bar' => 'baz'
			)
		);

		$sut = $this->component;

		$this->assertEquals(100, $sut->getPost('key'));
		$this->assertEquals('baz', $sut->getPost('foo.bar'));
		$this->assertEquals(null, $sut->getPost('invalid'));

		$this->assertEquals('default', $sut->getPostOrElse('invalid', 'default'));

		$called = false;
		$val = $sut->getPostOrCall('key', function() use(&$called) {
			$called = true;
			return 'value';
		});
		$this->assertFalse($called);
		$this->assertEquals(100, $val);

		$val = $sut->getPostOrCall('invalid', function() use(&$called) {
			$called = true;
			return 'value';
		});
		$this->assertTrue($called);
		$this->assertEquals('value', $val);

		$ex = new \Exception('test');
		$val = $sut->getPostOrThrow('key', $ex);
		$this->assertEquals(100, $val);

		try {
			$val = $sut->getPostOrThrow('invalid', $ex);
			$this->fail('No Exception raised');
		} catch (\Exception $e) {
			$this->assertEquals('test', $e->getMessage());
		}

		$_POST = $restore;
	}

	public function testGetQueryFunctions() {
		$restore = $_GET;
		$_GET = array(
			'key' => 100,
			'foo' => array(
				'bar' => 'baz'
			)
		);

		$sut = $this->component;

		$this->assertEquals(100, $sut->getQuery('key'));
		$this->assertEquals('baz', $sut->getQuery('foo.bar'));
		$this->assertEquals(null, $sut->getQuery('invalid'));

		$this->assertEquals('default', $sut->getQueryOrElse('invalid', 'default'));

		$called = false;
		$val = $sut->getQueryOrCall('key', function() use(&$called) {
			$called = true;
			return 'value';
		});
		$this->assertFalse($called);
		$this->assertEquals(100, $val);

		$val = $sut->getQueryOrCall('invalid', function() use(&$called) {
			$called = true;
			return 'value';
		});
		$this->assertTrue($called);
		$this->assertEquals('value', $val);

		$ex = new \Exception('test');
		$val = $sut->getQueryOrThrow('key', $ex);
		$this->assertEquals(100, $val);

		try {
			$val = $sut->getQueryOrThrow('invalid', $ex);
			$this->fail('No Exception raised');
		} catch (\Exception $e) {
			$this->assertEquals('test', $e->getMessage());
		}

		$_GET = $restore;
	}

	public function testGetInputFunctions() {
		$restoreGet = $_GET;
		$restorePost = $_POST;
		$_GET = array(
			'key' => 'get',
			'g' => 100,
			'query' => array(
				'method' => 'get'
			)
		);
		$_POST = array(
			'key' => 'post',
			'p' => 100,
			'foo' => array(
				'bar' => 'baz'
			)
		);

		$sut = $this->component;

		$this->assertEquals('post', $sut->getInput('key'));
		$this->assertEquals(100, $sut->getInput('g'));
		$this->assertEquals(100, $sut->getInput('p'));
		$this->assertEquals('baz', $sut->getInput('foo.bar'));
		$this->assertEquals('get', $sut->getInput('query.method'));
		$this->assertEquals(null, $sut->getInput('invalid'));

		$this->assertEquals('default', $sut->getInputOrElse('invalid', 'default'));

		$called = false;
		$val = $sut->getInputOrCall('key', function() use(&$called) {
			$called = true;
			return 'value';
		});
		$this->assertFalse($called);
		$this->assertEquals('post', $val);

		$val = $sut->getInputOrCall('invalid', function() use(&$called) {
			$called = true;
			return 'value';
		});
		$this->assertTrue($called);
		$this->assertEquals('value', $val);

		$ex = new \Exception('test');
		$val = $sut->getInputOrThrow('key', $ex);
		$this->assertEquals('post', $val);

		try {
			$val = $sut->getInputOrThrow('invalid', $ex);
			$this->fail('No Exception raised');
		} catch (\Exception $e) {
			$this->assertEquals('test', $e->getMessage());
		}

		$_GET = $restoreGet;
		$_POST = $restorePost;
	}
}
