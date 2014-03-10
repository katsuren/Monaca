<?php

App::uses('Controller', 'Controller');
App::uses('MonadComponent', 'Monaca.Controller/Component');
App::uses('MonadView', 'Monaca.View');

/**
 * @package Monaca
 * @subpackage Controller
 */
class MonadController extends Controller {

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
