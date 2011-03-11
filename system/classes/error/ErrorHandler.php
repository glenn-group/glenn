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
		
		// Let's grab those mother-fuhring fatal errors!
		// Kind of a hack so make sure we exit afterwards.
		\ini_set('display_errors', 0);
		\register_shutdown_function(function ($handler) {
			$error = error_get_last();
			if ($error['type'] == 1) {
				\call_user_func_array($handler, 
						array($error['type'], $error['message'], $error['file'], $error['line']));
				exit($error['type']);
			}
		}, $handler);

	}

	public static function defaultErrorHandler($type, $string, $file, $line)
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
		
		include \glenn\loader\Loader::findView('error');
		
		// Halt all execution. TODO: Should depend on error reporting settings.
		exit(-1);
		// The normal course of action, if we do not exit.
		return true;
	}

}