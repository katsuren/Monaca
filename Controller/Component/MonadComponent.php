<?php

App::uses('Component', 'Controller');
App::uses('CakeSession', 'Model/Datasource');
App::uses('Monaca', 'Monaca.Lib');
App::uses('Hash', 'Utility');

/**
 * @package Monaca
 * @subpackage Controller.Component
 */
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
		return Monaca::getConfig($key);
	}

	public function getConfigOrElse($key, $default = null) {
		return Monaca::getConfigOrElse($key, $default);
	}

	public function getConfigOrCall($key, callable $function) {
		return Monaca::getConfigOrCall($key, $function);
	}

/**
 * @throws \Exception
 */
	public function getConfigOrThrow($key, \Exception $ex) {
		return Monaca::getConfigOrThrow($key, $ex);
	}

	//-----------
	// Session
	//-----------

	public function getSession($key) {
		return Monaca::getSession($key);
	}

	public function getSessionOrElse($key, $default = null) {
		return Monaca::getSessionOrElse($key, $default);
	}

	public function getSessionOrCall($key, callable $function) {
		return Monaca::getSessionOrCall($key, $function);
	}

/**
 * @throws \Exception
 */
	public function getSessionOrThrow($key, \Exception $ex) {
		return Monaca::getSessionOrThrow($key, $ex);
	}

	//-----------
	// POST
	//-----------

	public function getPost($key) {
		return $this->getPostOrElse($key, null);
	}

	public function getPostOrElse($key, $default = null) {
		return $this->getPostOrCall($key, function() use($default) {
			return $default;
		});
	}

	public function getPostOrCall($key, callable $function) {
		$ret = is_null($key) ? $_POST : Hash::get($_POST, $key);
		return is_null($ret) ? $function() : $ret;
	}

/**
 * @throws \Exception
 */
	public function getPostOrThrow($key, \Exception $ex) {
		return $this->getPostOrCall($key, function() use($ex) {
			throw $ex;
		});
	}

	//-----------
	// Get query
	//-----------

	public function getQuery($key = null) {
		return $this->getQueryOrElse($key, null);
	}

	public function getQueryOrElse($key, $default = null) {
		return $this->getQueryOrCall($key, function() use($default) {
			return $default;
		});
	}

	public function getQueryOrCall($key, callable $function) {
		$ret = is_null($key) ? $_GET : Hash::get($_GET, $key);
		return is_null($ret) ? $function() : $ret;
	}

/**
 * @throws \Exception
 */
	public function getQueryOrThrow($key, \Exception $ex) {
		return $this->getQueryOrCall($key, function() use($ex) {
			throw $ex;
		});
	}

	//-----------
	// Input
	//-----------

	public function getInput($key) {
		return $this->getInputOrElse($key, null);
	}

	public function getInputOrElse($key, $default = null) {
		return $this->getPostOrCall($key, function() use($key, $default) {
			return $this->getQueryOrElse($key, $default);
		});
	}

	public function getInputOrCall($key, callable $function) {
		return $this->getPostOrCall($key, function() use($key, $function) {
			return $this->getQueryOrCall($key, function() use($function) {
				return $function();
			});
		});
	}

/**
 * @throws \Exception
 */
	public function getInputOrThrow($key, \Exception $ex) {
		return $this->getPostOrCall($key, function() use($key, $ex) {
			return $this->getQueryOrThrow($key, $ex);
		});
	}
}
