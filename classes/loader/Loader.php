<?php
namespace glenn\loader;

class Loader
{
	/**
	 *
	 * @var string
	 */
	protected static $classPrefix = 'classes';
	
	/**
	 *
	 * @var string
	 */
	protected static $classSuffix = '.php';
	
	/**
	 *
	 * @var array
	 */
	protected static $modules = array();
	
	/**
	 * 
	 */
	public static function bootstrapModules()
	{
		$modules = \array_reverse(self::$modules);
		foreach ($modules as $module => $path) {
			$bootstrap = $path.'bootstrap.php';
			if (\file_exists($bootstrap)) {
				require($bootstrap);
			}
		}
	}
	
	public static function isRegistered($module)
	{
		return \array_key_exists($module, self::$modules);
	}
	
	/**
	 *
	 * @param string $class 
	 */
	public static function load($class)
	{
		foreach (self::$modules as $module => $path) {
			if (\strpos($class, $module) === 0) {
				require(
					$path.
					self::$classPrefix.
					\str_replace('\\', '/', \substr($class, \strlen($module))).
					self::$classSuffix
				);
				break;
			}
		}
	}
	
	/**
	 *
	 * @return array
	 */
	public static function modules()
	{
		return self::$modules;
	}
	
	/**
	 * 
	 */
	public static function registerAutoloader()
	{
		\spl_autoload_register(array(__CLASS__, 'load'));
	}
	
	/**
	 *
	 * @param string $module
	 * @param string $path 
	 */
	public static function registerModule($module, $path)
	{
		self::$modules[$module] = $path;
	}
	
	/**
	 *
	 * @param array $modules 
	 */
	public static function registerModules(array $modules)
	{
		foreach ($modules as $module => $path) {
			self::registerModule($module, $path);
		}
	}
	
	/**
	 *
	 * @param string $prefix 
	 */
	public static function setClassPrefix($prefix)
	{
		self::$classPrefix = $prefix;
	}
	
	/**
	 *
	 * @param string $suffix 
	 */
	public static function setClassSuffix($suffix)
	{
		self::$classSuffix = $suffix;
	}
}