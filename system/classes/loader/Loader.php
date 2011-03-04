<?php
namespace glenn\loader;

class Loader
{
	protected static $modules = array();
	
	public static function load($class)
	{
		foreach (self::$modules as $module => $path) {
			if (\strpos($class, $module) === 0) {
				require $path 
					    . 'classes' 
					    . str_replace('\\', '/', \substr($class, \strlen($module))) 
						. '.php';
				break;
			}
		}
	}
	
	public static function registerAutoloader()
	{
		\spl_autoload_register(array(__CLASS__, 'load'));
	}
	
	public static function registerModule($module, $path)
	{
		self::$modules[$module] = $path;
	}
	
	public static function registerModules(array $modules)
	{
		foreach ($modules as $module => $path) {
			self::registerModule($module, $path);
		}
	}
}