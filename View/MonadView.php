<?php

App::uses('View', 'View');
App::uses('Maybe', 'Monaca.Lib');
App::uses('Hash', 'Utility');

class MonadView extends View {

	public function getOrElse($var, $default = null) {
		return $this->getOrCall($var, function() use($default) {
			return $default;
		});
	}

	public function getOrCall($var, callable $function) {
		$ret = Hash::get($this->viewVars, $var);
		return is_null($ret) ? $function() : $ret;
	}

/**
 * @throws \Exception
 */
	public function getOrThrow($var, \Exception $ex) {
		return $this->getOrCall($var, function() use($ex) {
			throw $ex;
		});
	}
}
