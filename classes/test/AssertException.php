<?php

namespace glenn\test;

class AssertException extends \Exception
{
	
	public function __construct($assertion = null, $data = null)
	{
		if($assertion === null) {
			$message = 'Assertion failed.';
		} else {
			$message = 'Assertion \'assert' . \ucfirst($assertion) . '\' failed';
			if($data !== null && \strlen($data) > 0) {
				$message .= " with data '$data'.";
			} else {
				$message .= '.';
			}
		}
		parent::__construct($message, null, null);
	}
	
}