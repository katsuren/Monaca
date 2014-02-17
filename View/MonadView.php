<?php

App::uses('View', 'View');
App::uses('Maybe', 'Monaca.Lib');

class MonadView extends View {

	public function getMaybe($var, $default = null) {
		if (isset($this->viewVars[$var])) {
			return new Maybe($this->viewVars[$var]);
		}
		return new Maybe($default);
	}
}
