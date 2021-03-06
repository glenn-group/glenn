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

	public static function isRegistered($module)
	{
		return \array_key_exists($module, self::$modules);
	}
	
	/**
	 *
	 * @param string $class 
	 */
	public static function load($className)
	{
		if(\strpos($className, '_') === 0) {
			$class = static::resolve(\substr($className, 1));
			$parent = 'glenn'.  \substr($className, 1);
			// Check if class is valid (interfaces not supported at this moment!)
			if ($class !== $parent && !\is_subclass_of($class, $parent)) {
				throw new \Exception("Class $class is not a subclass of $className.");
			}
		} else {
			$class = $className;
		}
		foreach (self::$modules as $module => $path) {
			if (\strpos($class, $module) === 0) {
				require_once(
					$path.
					self::$classPrefix.
					\str_replace('\\', '/', \substr($class, \strlen($module))).
					self::$classSuffix
				);
				if($class !== $className) {
					\class_alias($class, $className);
				}
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
	public static function find($type, $file)
	{
		$filePath = $type . DIRECTORY_SEPARATOR . $file;
		foreach (self::$modules as $module => $path) {
			if (\file_exists($path . $filePath)) {
				return $path . $filePath;
			}
		}
	}
	
	/**
	 * Locate a class as high up as possible in the list of modules.
	 *
	 * @param string $view 
	 * @return string path to view
	 */
	public static function resolve($class)
	{
		if(\substr($class, 0, 1) !== '\\') {
			// Absolute path
			return $class;
		} else {
			// Relative path, search through modules
			$config = \glenn\config\Config::factory('classes.php');
			if(isset($config->$class)) {
				return $config->$class;
			}
			return 'glenn' . $class;
		}
		return false;
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