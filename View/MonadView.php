<?php

App::uses('View', 'View');
App::uses('Maybe', 'Monaca.Lib');
App::uses('Hash', 'Utility');

class MonadView extends View {

	public function getOrElse($var, $default = null) {
		$ret = Hash::get($this->viewVars, $var);
		if ($ret !== null) {
			return $ret;
		}
		return $default;
	}

	public function getOrCall($var, callable $function) {
		$ret = $this->getOrElse($var, null);
		if ($ret !== null) {
			return $ret;
		}
		return $function();
	}

/**
 * @throws \Exception
 */
	public function getOrThrow($var, \Exception $ex) {
		$ret = $this->getOrElse($var, null);
		if ($ret !== null) {
			return $ret;
		}
		throw $ex;
	}
}
