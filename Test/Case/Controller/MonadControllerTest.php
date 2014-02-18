<?php

App::uses('MonadController', 'Monaca.Controller');
App::uses('MonadComponent', 'Monaca.Controller/Component');

class MonadControllerTest extends ControllerTestCase {

	//-----------------------
	//
	// Properties
	//
	//-----------------------

	public $controller;

	//-----------------------
	//
	// Preparation
	//
	//-----------------------

	public function setUp() {
		$this->controller = $this->generate('Monaca.Monad');
	}

	public function testDefaultViewClass() {
		$this->assertEquals($this->controller->viewClass, 'Monaca.Monad');
	}
}
