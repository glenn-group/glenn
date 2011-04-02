<?php
namespace glenn\http;

class Request extends Message
{
	/**
	 * @var string
	 */
	protected $uri;
	
	/**
	 * @var string
	 */
    protected $method;
	
	/**
	 * @var string
	 */
    protected $ajax;
	
	/**
	 * @var string
	 */
    protected $secure;
	
	/**
	 * @var array
	 */
	protected $params = array();
	
	/**
	 * @param string $uri
	 * @param string $method
	 */
	public function __construct($uri = null, $method = null, $headers = array())
	{
        if ($uri !== null) {
            $this->uri = $uri;
        } else if ($_SERVER['REQUEST_URI'] !== null) {
            $this->uri = $_SERVER['REQUEST_URI'];
        }
        
        if ($method !== null) {
            $this->method = $method;
        } else if ($_SERVER['REQUEST_METHOD'] !== null) {
            $this->method = $_SERVER['REQUEST_METHOD'];
        }
        
		if (\strpos($this->uri, 'https://') === 0) {
			$this->secure = true;
		} else {
			$this->secure = false;
		}
		
		// Check if this request is an AJAX request
		if ( (\strpos($this->uri, 'https://') === 0 || \strpos($this->uri, 'http://') === 0)
				&& isset($_SERVER['HTTP_X_REQUESTED_WITH'])
				&& \strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
			$this->ajax = true;
		} else {
			$this->ajax = false;
		}
		
        $this->addHeader('Host', $this->hostname());
    }
	
	/**
	 * @return string
	 */
	public function uri()
	{
		return $this->uri;
	}
	
	/**
	 * @return string
	 */
	public function method()
	{
		return $this->method;
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
	public function hostname()
	{
		if (\strpos($this->uri, 'http://') === 0) {
			return \substr($this->uri, 7, \strpos($this->uri(), '/', 7) - 7);
		} else if (\strpos($this->uri, 'https://') === 0) {
			return \substr($this->uri, 8, \strpos($this->uri(), '/', 8) - 8);
		}
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