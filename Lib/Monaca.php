<?php

App::uses('CakeSession', 'Model/Datasource');
App::uses('Hash', 'Utility');

/**
 * @package Monaca
 * @subpackage Lib
 */
class Monaca {

	//-----------------------------
	//
	// Public methods
	//
	//-----------------------------

	//-----------
	// Configure
	//-----------

	public static function getConfig($key) {
		return self::getConfigOrElse($key, null);
	}

	public static function getConfigOrElse($key, $default = null) {
		return self::getConfigOrCall($key, function() use($default) {
			return $default;
		});
	}

	public static function getConfigOrCall($key, callable $function) {
		return Configure::check($key) ? Configure::read($key) : $function();
	}

/**
 * @throws \Exception
 */
	public static function getConfigOrThrow($key, \Exception $ex) {
		return self::getConfigOrCall($key, function() use($ex) {
			throw $ex;
		});
	}

	//-----------
	// Session
	//-----------

	public static function getSession($key) {
		return self::getSessionOrElse($key, null);
	}

	public static function getSessionOrElse($key, $default = null) {
		return self::getSessionOrCall($key, function() use($default) {
			return $default;
		});
	}

	public static function getSessionOrCall($key, callable $function) {
		return CakeSession::check($key) ? CakeSession::read($key) : $function();
	}

/**
 * @throws \Exception
 */
	public static function getSessionOrThrow($key, \Exception $ex) {
		return self::getSessionOrCall($key, function() use($ex) {
			throw $ex;
		});
	}
}
