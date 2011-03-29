<?php

namespace glenn\http;

class Cookie
{
	private $name;
	private $value;
	private $expiry;
	private $path;
	private $domain;
	private $secure;
	private $httponly;
	
	protected static $defaults = array(
		'name' => null,
		'value' => null,
		'expiry' => 0,
		'path' => '',
		'domain' => '',
		'secure' => false,
		'httponly' => true
	);

	/**
	 * The Cookie constructor takes the same parameters as the setcookie function of PHP.
	 * The only difference is that HttpOnly normally defaults to false, however this has been
	 * changed to true for security purposes.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @param string $path
	 * @param string $domain
	 * @param boolean $secure
	 * @param boolean $httponly 
	 */
	public function __construct($name, $value, $expiry = 0, $path = '', $domain = '', $secure = false, $httponly = true)
	{
		$this->name = $name;
		$this->value = $value;
		$this->expiry = $expiry;
		$this->path = $path;
		$this->domain = ($domain === null) ? $_SERVER['HTTP_HOST'] : $domain;
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
		$data = \get_object_vars($this);
		unset($data['name']);
		foreach ($data as $key => $value) {
			if(static::$defaults[$key] === $value) {
				unset($data[$key]);
			}
		}
		$data = \urlencode(\serialize($data));
		setcookie($this->name, $data, $this->expiry, $this->path, $this->domain, $this->secure, $this->httponly);
	}
	
	public function delete()
	{
		setcookie($this->name, '', time() - 3600*25, $this->path, $this->domain);
		unset($_COOKIE[$this->name]);
	}
	
	public function getName() 	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getValue()
	{
		return $this->value;
	}

	public function setValue($value)
	{
		$this->value = $value;
	}

	public function getPath()
	{
		return $this->path;
	}

	public function setPath($path)
	{
		$this->path = $path;
	}

	public function getDomain()
	{
		return $this->domain;
	}

	public function setDomain($domain)
	{
		$this->domain = $domain;
	}

	public function isSecure()
	{
		return $this->secure;
	}

	public function setSecure($secure)
	{
		$this->secure = $secure;
	}

	public function getHttpOnly()
	{
		return $this->httponly;
	}

	public function setHttpOnly($httponly)
	{
		$this->httponly = $httponly;
	}
	
	public function __sleep()
	{
		$fields = array('name', 'value');
		if($this->path !== '') {
			$fields[] = 'path';
		}
		if($this->domain !== '') {
			$fields[] = 'domain';
		}
		if($this->expiry !== 0) {
			$fields[] = 'expiry';
		}
		return $fields;
	}
	
	public function __wakeup()
	{
		if($this->path === null) {
			$this->path = '';
		}
		if($this->domain === null) {
			$this->domain = '';
		}
		if($this->expiry === null) {
			$this->expiry = 0;
		}
	}

}