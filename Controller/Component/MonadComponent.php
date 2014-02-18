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
		if (Configure::check($key)) {
			return Configure::read($key);
		}
		return $default;
	}

	public function getConfigOrCall($key, callable $function) {
		if (Configure::check($key)) {
			return Configure::read($key);
		}
		return $function();
	}

/**
 * @throws \Exception
 */
	public function getConfigOrThrow($key, \Exception $ex) {
		if (Configure::check($key)) {
			return Configure::read($key);
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
			return CakeSession::read($key);
		}
		return $default;
	}

	public function getSessionOrCall($key, callable $function) {
		if (CakeSession::check($key)) {
			return CakeSession::read($key);
		}
		return $function();
	}

/**
 * @throws \Exception
 */
	public function getSessionOrThrow($key, \Exception $ex) {
		if (CakeSession::check($key)) {
			return CakeSession::read($key);
		}
		throw $ex;
	}

	//-----------
	// POST
	//-----------

	public function getPost($key) {
		return $this->getPostOrElse($key, null);
	}

	public function getPostOrElse($key, $default = null) {
		if ($key === null) {
			return $_POST;
		}
		$ret = Hash::get($_POST, $key);
		if ($ret !== null) {
			return $ret;
		}
		return $default;
	}

	public function getPostOrCall($key, callable $function) {
		$ret = $this->getPostOrElse($key, null);
		if ($ret !== null) {
			return $ret;
		}
		return $function();
	}

/**
 * @throws \Exception
 */
	public function getPostOrThrow($key, \Exception $ex) {
		$ret = $this->getPostOrElse($key, null);
		if ($ret !== null) {
			return $ret;
		}
		throw $ex;
	}

	//-----------
	// Get query
	//-----------

	public function getQuery($key = null) {
		return $this->getQueryOrElse($key, null);
	}

	public function getQueryOrElse($key, $default = null) {
		if ($key === null) {
			return $_GET;
		}
		$ret = Hash::get($_GET, $key);
		if ($ret !== null) {
			return $ret;
		}
		return $default;
	}

	public function getQueryOrCall($key, callable $function) {
		$ret = $this->getQueryOrElse($key, null);
		if ($ret !== null) {
			return $ret;
		}
		return $function();
	}

/**
 * @throws \Exception
 */
	public function getQueryOrThrow($key, \Exception $ex) {
		$ret = $this->getQueryOrElse($key, null);
		if ($ret !== null) {
			return $ret;
		}
		throw $ex;
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
