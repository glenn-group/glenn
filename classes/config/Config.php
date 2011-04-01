<?php
namespace glenn\config;

class Config
{
	
	public static function factory($configFile)
	{
		$file = \glenn\loader\Loader::find('config', $configFile);
		// If no such file is found, return a default implementation with no config values
		if(!$file) {
			return new adapter\PhpArray(array());
		}
		switch (pathinfo($configFile, PATHINFO_EXTENSION)) {
			case 'php':				
				include $file;
				if(!isset($config))
					$config = array();
				return new adapter\PhpArray($config);
			case 'ini':
				return new adapter\Ini($file);
			default:
				throw new \Exception('No such Config adapter available.');
		}
	}

	
}