<?php

namespace glenn\security;

class Random
{

	/**
	 * 
	 * @author phpass
	 * @param type $count
	 * @return type 
	 */
	public static function getRandomBytes($count)
	{
		$output = '';
		if (is_readable('/dev/urandom') && ($fh = @fopen('/dev/urandom', 'rb'))) {
			$output = fread($fh, $count);
			fclose($fh);
		}

		if (strlen($output) < $count) {
			$output = '';
			for ($i = 0; $i < $count; $i += 16) {
				$randomState = microtime();
				if (function_exists('getmypid'))
					$this->random_state .= getmypid();
				$randomState = md5(microtime() . $randomState);
				$output .= pack('H*', md5($randomState));
			}
			$output = substr($output, 0, $count);
		}

		return $output;
	}

}