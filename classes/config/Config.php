<?php
namespace glenn\config;

class Config
{
	
	public static function factory($configFile)
	{
		$file = \glenn\loader\Loader::find('config', $configFile);
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