<?php

App::uses('Monad', 'Monaca.Lib');
App::uses('Maybe', 'Monaca.Lib');

class HashMonad extends Monad {

	//-----------------------------
	//
	// Constants
	//
	//-----------------------------
	const UNIT = "HashMonad::unit";


	//-----------------------------
	//
	// Public methods
	//
	//-----------------------------

/**
 * Constructor
 * @throws \InvalidArgumentException
 */
	public function __construct($value) {
		if (!is_array($value) && !$value instanceof \Traversable) {
			throw new \InvalidArgumentException('Must be traversable');
		}
		return parent::__construct($value);
	}

	public function bind($function, array $args = array()) {
		$result = array();
		foreach ($this->_value as $key => $value) {
			$result[$key] = $this->_runCallback($function, $value, $args);
		}
		return $this::unit($result);
	}

	public function extract() {
		$ret = array();
		foreach ($this->_value as $key => $value) {
			if ($value instanceof Monad) {
				$ret[$key] = $value->extract();
			} else {
				$ret[$key] = $value;
			}
		}
		return $ret;
	}

	public function get($key) {
		return $this->getOrElse($key, null);
	}

	public function getOrElse($key, $default = null) {
		if (isset($this->_value[$key])) {
			return new Maybe($this->_value[$key]);
		}
		return new Maybe($default);
	}

	public function getOrCall($key, callable $function) {
		if (isset($this->_value[$key])) {
			return new Maybe($this->_value[$key]);
		}
		return new Maybe($function());
	}

/**
 * @throws \Exception
 */
	public function getOrThrow($key, \Exception $ex) {
		if (isset($this->_value[$key])) {
			return new Maybe($this->_value[$key]);
		}
		throw $ex;
	}
}
