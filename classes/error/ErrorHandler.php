<?php

namespace glenn\error;

class ErrorHandler
{
	protected static $linesAround = 7;

	public static function register($handler = null)
	{
		if ($handler === null) {
			$handler = array(__CLASS__, 'defaultErrorHandler');
		}
		
		\set_error_handler($handler);
		\set_exception_handler(array(__CLASS__, 'defaultExceptionHandler'));
	}

	public static function defaultErrorHandler($type, $string = null, $file = null, $line = null)
	{
		// Skip errors that shouldn't be reported
		if (!(error_reporting() & $type)) {
			return;
		}

		$code = file($file);

		$start = ($line <= self::$linesAround + 1) ? 0 : $line - self::$linesAround - 1;
		$end = (count($code) <= $line + self::$linesAround) ? count($code) : $line + self::$linesAround;

		$errcode = array();
		for ($i = $start; $i < $end; $i++) {
			$errcode[$i+1] = htmlentities($code[$i]);
		}
		$start++;
		$end++;
		
		// Clean output buffer if it's in use so we only print the error.
		@ob_end_clean();
		
		include \glenn\loader\Loader::find('views', 'error.php');
		
		// Halt all execution. TODO: Should depend on error reporting settings.
		exit(-1);
		// The normal course of action, if we do not exit.
		return true;
	}
	
	public static function defaultExceptionHandler($exception)
	{
		$file = $exception->getFile();
		$line = $exception->getLine();
		$string = $exception->getMessage();
		$type = $exception->getCode();
		$code = file($file);

		$start = ($line <= self::$linesAround + 1) ? 0 : $line - self::$linesAround - 1;
		$end = (count($code) <= $line + self::$linesAround) ? count($code) : $line + self::$linesAround;

		$errcode = array();
		for ($i = $start; $i < $end; $i++) {
			$errcode[$i+1] = htmlentities($code[$i]);
		}
		$start++;
		$end++;
		
		// Clean output buffer if it's in use so we only print the error.
		@ob_end_clean();
		
		include \glenn\loader\Loader::find('views', 'error.php');
		
		// Halt all execution. TODO: Should depend on error reporting settings.
		exit(-1);
		// The normal course of action, if we do not exit.
		return true;
	}

}