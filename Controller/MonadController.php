<?php

App::uses('Controller', 'Controller');
App::uses('HashMonad', 'Monaca.Lib');
App::uses('MonadComponent', 'Monaca.Controller/Component');
App::uses('MonadView', 'Monaca.View');

class MonadController extends Controller {

	//--------------------------------------
	//
	// Properties
	//
	//--------------------------------------
	public $components = array('Monad');

	public $requestData;

	public $requestQuery;


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

		$this->requestData = new HashMonad($request->data);
		$this->requestQuery = new HashMonad($request->query);
	}

	public function beforeFilter() {
		parent::beforeFilter();

		$this->viewClass = 'Monaca.Monad';
	}
}
