<?php

App::uses('Component', 'Controller');
App::uses('CakeSession', 'Model/Datasource');
App::uses('Maybe', 'Monaca.Lib');
App::uses('Hash', 'Utility');

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
		return $this->getConfigOrCall($key, function() use($default) {
			return $default;
		});
	}

	public function getConfigOrCall($key, callable $function) {
		return Configure::check($key) ? Configure::read($key) : $function();
	}

/**
 * @throws \Exception
 */
	public function getConfigOrThrow($key, \Exception $ex) {
		return $this->getConfigOrCall($key, function() use($ex) {
			throw $ex;
		});
	}

	//-----------
	// Session
	//-----------

	public function getSession($key) {
		return $this->getSessionOrElse($key, null);
	}

	public function getSessionOrElse($key, $default = null) {
		return $this->getSessionOrCall($key, function() use($default) {
			return $default;
		});
	}

	public function getSessionOrCall($key, callable $function) {
		return CakeSession::check($key) ? CakeSession::read($key) : $function();
	}

/**
 * @throws \Exception
 */
	public function getSessionOrThrow($key, \Exception $ex) {
		return $this->getSessionOrCall($key, function() use($ex) {
			throw $ex;
		});
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
