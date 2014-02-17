<?php

App::uses('Component', 'Controller');
App::uses('CakeSession', 'Model/Datasource');
App::uses('Maybe', 'Monaca.Lib');

class MonadComponent extends Component {

	//-----------------------------
	//
	// Public methods
	//
	//-----------------------------

	//-----------
	// Configure
	//-----------

	public function getConfig($key) {
		return $this->getConfigOrElse($key, null);
	}

	public function getConfigOrElse($key, $default = null) {
		if (Configure::check($key)) {
			return new Maybe(Configure::read($key));
		}
		return new Maybe($default);
	}

	public function getConfigOrCall($key, callable $function) {
		if (Configure::check($key)) {
			return new Maybe(Configure::read($key));
		}
		return new Maybe($function());
	}

/**
 * @throws \Exception
 */
	public function getConfigOrThrow($key, \Exception $ex) {
		if (Configure::check($key)) {
			return new Maybe(Configure::read($key));
		}
		throw $ex;
	}

	//-----------
	// Session
	//-----------

	public function getSession($key) {
		return $this->getSessionOrElse($key, null);
	}

	public function getSessionOrElse($key, $default = null) {
		if (CakeSession::check($key)) {
			return new Maybe(CakeSession::read($key));
		}
		return new Maybe($default);
	}

	public function getSessionOrCall($key, callable $function) {
		if (CakeSession::check($key)) {
			return new Maybe(CakeSession::read($key));
		}
		return new Maybe($function());
	}

/**
 * @throws \Exception
 */
	public function getSessionOrThrow($key, \Exception $ex) {
		if (CakeSession::check($key)) {
			return new Maybe(CakeSession::read($key));
		}
		throw $ex;
	}
}
