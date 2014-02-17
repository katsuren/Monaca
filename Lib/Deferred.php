<?php

App::uses('Promise', 'Monaca.Lib');

class Deferred extends Promise {

	public function succeed($value) {
		$this->_resolve(true, $value);
	}

	public function fail($value) {
		$this->_resolve(false, $value);
	}
}
