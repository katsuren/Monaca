<?php

App::uses('Monad', 'Monaca.Lib');

/**
 * @package Monaca.Lib
 */
class ListMonad extends Monad {

	//-----------------------------
	//
	// Constants
	//
	//-----------------------------
	const UNIT = "ListMonad::unit";


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
		foreach ($this->_value as $value) {
			$result[] = $this->_runCallback($function, $value, $args);
		}
		return $this::unit($result);
	}

	public function extract() {
		$ret = array();
		foreach ($this->_value as $value) {
			if ($value instanceof Monad) {
				$ret[] = $value->extract();
			} else {
				$ret[] = $value;
			}
		}
		return $ret;
	}
}
