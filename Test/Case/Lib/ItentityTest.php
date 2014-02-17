<?php

App::uses('Identity', 'Monaca.Lib');
App::uses('Maybe', 'Monaca.Lib');

class IdentityTest extends CakeTestCase {

	public function testBind() {
		$monad = new Identity(1);
		$this->assertEquals($monad->unit(1), $monad->bind('intval'));
	}

	public function testBindUnit() {
		$monad = new Identity(1);
		$this->assertEquals($monad, $monad->bind($monad::UNIT));
	}

	public function testExtract() {
		$monad = new Identity(new Maybe(1));
		$this->assertEquals(1, $monad->extract());
	}
}
