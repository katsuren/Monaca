<?php

App::uses('Hash', 'Utility');

/**
 * @package Monaca.Lib
 */
abstract class Monad {

	//-------------------------------------------
	//
	// Properties
	//
	//-------------------------------------------
	protected $_value;

	//-------------------------------------------
	//
	// Public methods
	//
	//-------------------------------------------

/**
 * Constructor
 */
	public function __construct($value) {
		$this->_value = $value;
	}

	public static function unit($value) {
		if ($value instanceof static) {
			return $value;
		}
		return new static($value);
	}

	public function extract() {
		if ($this->_value instanceof self) {
			return $this->_value->extract();
		}
		return $this->_value;
	}

/**
 * @throws \BadMethodCallException
 */
	public function __call($name, $arguments) {
		if ($name == 'bind') {
			$function = array_shift($arguments);
			$args = empty($arguments) ? array() : array_shift($arguments);
			return $this::unit($this->_runCallback($function, $this->_value, $args));
		}
		throw new \BadMethodCallException('Call to undefined method ' . $name);
	}

	//-------------
	// Optional
	//-------------

	public function get($key) {
		return $this->getOrElse($key, null);
	}

	public function getOrElse($key = null, $default = null) {
		$val = $this->extract();
		if (is_null($key)) {
			return is_null($val) ? $default : $val;
		}
		if (is_null($val)) {
			return $default;
		} elseif (!is_array($val)) {
			return $val;
		}
		$ret = Hash::get($val, $key);
		return is_null($ret) ? $default : $ret;
	}

	public function getOrCall($key, callable $function) {
		$val = $this->getOrElse($key, null);
		return is_null($val) ? $function() : $val;
	}

/**
 * @throws \Exception
 */
	public function getOrThrow($key, \Exception $ex) {
		return $this->getOrCall($key, function() use($ex) {
			throw $ex;
		});
	}

	//-------------------------------------------
	//
	// Private methods
	//
	//-------------------------------------------

	protected function _runCallback($function, $value, array $args = array()) {
		if ($value instanceof self) {
			return $value->bind($function, $args);
		}
		array_unshift($args, $value);
		return call_user_func_array($function, $args);
	}
}
