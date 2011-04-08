<?php

namespace glenn\http;

/**
 * Class for managing HTTP cookies in an object oriented manner.
 */
class Cookie
{
	/** Cookie data **/
	private $name;
	private $value;
	private $maxAge;
	private $path;
	private $domain;
	private $secure;
	private $httponly;
	
	/** Constants for cookie max age **/
	const MINUTE = 60;
	const HOUR = 3600;
	const DAY = 86400;
	const WEEK = 604800;
	
	protected static $defaults = array(
		'name' => null,
		'value' => null,
		'maxAge' => 0,
		'path' => '',
		'domain' => '',
		'secure' => false,
		'httponly' => true
	);

	/**
	 * The Cookie constructor takes the same parameters as the setcookie function of PHP.
	 * The only differences are that HttpOnly normally defaults to false, however this has been
	 * changed to true for security purposes, and Max-Age is used instead of Expires, meaning it
	 * only takes the number of seconds until expiry.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @param int $maxAge
	 * @param string $path
	 * @param string $domain
	 * @param boolean $secure
	 * @param boolean $httponly 
	 */
	public function __construct($name, $value, $maxAge = 0, $path = '', $domain = '', $secure = false, $httponly = true)
	{
		$this->name = $name;
		$this->value = $value;
		$this->maxAge = $maxAge;
		$this->path = $path;
		$this->domain = $domain;
		$this->secure = $secure;
		$this->httponly = $httponly;
	}
	
	/**
	 * Return a specified cookie object from the user's cookies.
	 *
	 * @param type $name
	 * @return type 
	 */
	public static function get($name)
	{
		if (!isset($_COOKIE[$name])) {
			return false;
		}
		$config = array_merge(static::$defaults, \unserialize(\urldecode($_COOKIE[$name])));
		$config['name'] = $name;
		$ref = new \ReflectionClass(\get_class());
		$cookie = $ref->newInstanceArgs($config);
		return $cookie;
	}
	
	/**
	 * Save all non-default values of the cookie object as a serialized array in a new cookie.
	 */
	public function save()
	{
		$data = array(
			'value' => $this->value,
			'domain' => $this->domain,
			'path' => $this->path
		);
		$data = \urlencode(\serialize($data));
		setcookie($this->name, $data, time() + $this->maxAge, $this->path, $this->domain, $this->secure, $this->httponly);
	}
	
	/**
	 * Remove this cookie from the user's computer.
	 */
	public function delete()
	{
		setcookie($this->name, '', time() - 3600*25, $this->path, $this->domain);
		unset($_COOKIE[$this->name]);
	}
	
	/**
	 * Return the name of this cookie.
	 * 
	 * @return string
	 */
	public function name() 	{
		return $this->name;
	}

	/**
	 * Return the value of the cookie. By default the value is sanitized but this can be overridden
	 * using the parameter.
	 *
	 * @param boolean $filter
	 * @return mixed 
	 */
	public function value($filter = true)
	{
		if ($filter === true) {
			return filter_var($this->value, FILTER_SANITIZE_STRING);
		} else {
			return $this->value;
		}
	}
	
	/**
	 * Set the value of this cookie instance.
	 *
	 * @param mixed $value 
	 */
	public function setValue($value)
	{
		$this->value = $value;
	}

}