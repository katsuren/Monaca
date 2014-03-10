<?php

App::uses('Monad', 'Monaca.Lib');

/**
 * @package Monaca
 * @package Lib
 */
class Promise extends Monad {

	//----------------------------
	//
	// Constants
	//
	//----------------------------
	const UNIT = "Promise::unit";

	//----------------------------
	//
	// Properties
	//
	//----------------------------
	protected $_isResolved = false;

	protected $_succeed = null;

	protected $_children = array();

	protected $_success;

	protected $_failure;


	//----------------------------
	//
	// Public methods
	//
	//----------------------------

/**
 * Constructor
 */
	public function __construct($success = null, $failure = null) {
		$this->_success = $success;
		$this->_failure = $failure;
	}

	public static function unit($success = null, $failure = null) {
		return new Promise($success, $failure);
	}

	public function bind($success, $failure) {
		$obj = $this->unit($success, $failure);
		if ($this->_isResolved) {
			$obj->_resolve($this->_succeed, $this->_value);
		} else {
			$this->_children[] = $obj;
		}
		return $obj;
	}

	public function when($success = null, $failure = null) {
		return $this->bind($success, $failure);
	}

	//----------------------------
	//
	// Private methods
	//
	//----------------------------

/**
 * @throws \BadMethodCallException
 */
	protected function _resolve($status, $value) {
		if ($this->_isResolved) {
			throw new \BadMethodCallException('Promise already resolved');
		}
		$this->_value = $value;
		$this->_isResolved = true;
		$this->_succeed = $status;
		$callback = $status ? $this->_success : $this->_failure;
		if ($callback) {
			$this->_value = call_user_func($callback, $value);
		}
		foreach ($this->_children as $child) {
			$child->_resolve($status, $this->_value);
		}
	}
}
