<?php
namespace glenn\http;

class Request extends Message
{
	/**
	 * @var string
	 */
    protected $ajax = false;

	/**
	 * @var string
	 */
	protected $hostname;

	/**
	 * @var string
	 */
    protected $method;
	
	/**
	 * @var array
	 */
	protected $params = array();
	
	/**
	 * @var string
	 */
	protected $scheme = '';
	
	/**
	 * @var string
	 */
    protected $secure = false;
	
	/**
	 * @var string
	 */
	protected $uri;
	
	/**
	 * @param string $uri
	 * @param string $method
	 */
	public function __construct($uri = null, $method = null, $headers = array())
	{
		// Let's start by setting the request uri
		if ($uri !== null) {
			$this->uri = $uri;
		} else if ($_SERVER['REQUEST_URI'] !== null) {
			$this->uri = $_SERVER['REQUEST_URI'];
		}

		// Next up, the request method needs some love
		if ($method !== null) {
			$this->method = $method;
		} else if (isset($_POST['_method'])) {
			$this->method = $_POST['_method'];
		} else if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
			$this->method = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];
		} else {
			$this->method = $_SERVER['REQUEST_METHOD'];
		}
        
		// Support http:// and https:// schemes
		if (\strpos($this->uri, 'http://') === 0) {
			$this->scheme = 'http://';
		} else if (\strpos($this->uri, 'https://') === 0) {
			$this->scheme = 'https://';
		}
		
		// Strip out the hostname from the uri
		$l = \strlen($this->scheme);
		if ($l > 0) {
			$this->hostname = \substr($this->uri, $l, \strpos($this->uri, '/', $l) - $l);
		} else {
			$this->hostname = $this->uri;
		}
		
		// Is the request secure?
		$this->secure = ($this->scheme() === 'https://') ?: true;
		
		// Is the request an ajax request?
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
			$this->ajax = true;
		}
		
		// Finally, add the host as a request header for extra niceness
        $this->addHeader('Host', $this->hostname());
    }
	
	/**
	 * @return string
	 */
	public function hostname()
	{	
		return $this->hostname;
	}
	
	/**
	 *
	 * @return boolean
	 */
	public function isAjax()
	{
		return $this->ajax;
	}
	
	/**
	 *
	 * @return boolean
	 */
	public function isSecure()
	{
		return $this->secure;
	}
	
	/**
	 * @return string
	 */
	public function method()
	{
		return $this->method;
	}
	
	/**
	 * @return string
	 */
	public function scheme()
	{
		return $this->scheme;
	}
	
	/**
	 * @return string
	 */
	public function uri()
	{
		return $this->uri;
	}
	
	/**
	 * Returns GET parameter $key. If no key is specified, the full GET 
	 * array is returned. Filtered by default.
	 * 
	 * @return array|string
	 */
    /*public function get($key = null, $filter = true)
    {
        return $this->param($key, $filter, INPUT_GET);
    }*/
    
    /**
	 * Returns POST parameter $key. If no key is specified, the full POST 
	 * array is returned. Filtered by default.
	 * 
	 * @return array|string
	 */
    /*public function post($key = null, $filter = true)
    {
        return $this->param($key, $filter, INPUT_POST);
    }*/
    
    /**
	 * Filters request parameters using native PHP filters. If no key is 
	 * specified, the full array is returned.
	 * 
	 * @return array|string
	 */
    /*protected function paramram($key, $filter, $type)
	{
        if ($key === null || $key === true) {
            return filter_input_array($type, FILTER_SANITIZE_STRING) ?: array();
        } else if ($key === false) {
            return filter_input_array($type, FILTER_UNSAFE_RAW) ?: array();
        } else if ($filter) {
            return filter_input($type, $key, FILTER_SANITIZE_STRING);
        } else {
            return filter_input($type, $key, FILTER_UNSAFE_RAW);
        }
    }*/
	
	public function param($key)
	{
		if (\array_key_exists($key, $this->params)) {
			return $this->params[$key];
		}
		throw new \Exception('No such parameter');
	}
	
	public function setParam($key, $value) 
	{
		$this->params[$key] = $value;
	}
	
	public function __get($key)
	{
		return $this->param($key);
	}
	
	public function __set($key, $value)
	{
		$this->setParam($key, $value);
	}
	
	/**
	 * @return string
	 */
    public function __toString() 
	{
		$request = \sprintf(
			"%s %s %s\r\n",
			$this->method, 
			$this->uri,
			$this->protocol
		);
		foreach ($this->headers as $key => $value) {
			$request .= \sprintf("%s: %s\r\n", $key, $value);
		}
		return $request .= "\r\n";
	}
}