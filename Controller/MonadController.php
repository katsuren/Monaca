<?php

App::uses('Controller', 'AppController');
App::uses('MonadComponent', 'Monaca.Controller/Component');
App::uses('MonadView', 'Monaca.View');

/**
 * @package Monaca.Controller
 */
class MonadController extends AppController {

	//--------------------------------------
	//
	// Properties
	//
	//--------------------------------------
	public $components = array('Monaca.Monad');


	//--------------------------------------
	//
	// Public methods
	//
	//--------------------------------------

/**
 * Constructor
 */
	public function __construct($request = null, $response = null) {
		parent::__construct($request, $response);

		$this->viewClass = 'Monaca.Monad';
	}
}
