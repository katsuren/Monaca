<?php

App::uses('Monad', 'Monaca.Lib');

class Maybe extends Monad {

	//------------------------------------
	//
	// Constants
	//
	//------------------------------------

	const UNIT = "Maybe::unit";


	//------------------------------------
	//
	// Public methods
	//
	//------------------------------------

	public function bind($function) {
		if (!is_null($this->_value)) {
			return parent::bind($function);
		}
		return $this::unit(null);
	}
}
