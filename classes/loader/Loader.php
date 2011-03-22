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
		foreach (self::$modules as $module => $path) {
			$bootstrap = $path.'bootstrap.php';
			if (\file_exists($bootstrap)) {
				require($bootstrap);
			}
		}
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
				return;
			}
		}
	}
	
	/**
	 * Locate a view file as high up as possible in the list of modules.
	 *
	 * @param string $view 
	 * @return string path to view
	 */
	public static function findView($view)
	{
		foreach (self::$modules as $module => $path) {
			if (\file_exists($path . 'views/' . $view . '.phtml')) {
				return $path . 'views/' . $view . '.phtml';
			}
		}
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