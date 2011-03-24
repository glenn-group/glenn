<?php
namespace glenn\http;

class Request extends Message
{
	/**
	 *
	 * @var array
	 */
	private $allowedMethods = array('POST', 'GET', 'PUT', 'DELETE');
	
	/**
	 * @var string
	 */
    protected $method;
	
	/**
	 * @var string
	 */
	protected $uri;
    
	/**
	 * Leaving the parameters of the constructor empty will cause the object to use the information
	 * of the current HTTP request. The request method can be overwritten using a POST parameter 
	 * named _method.
	 * 
	 * @param string $uri
	 * @param string $method
	 */
	public function __construct($uri = null, $method = null)
	{
        if ($uri !== null) {
            $this->uri = $uri;
        } else if ($_SERVER['REQUEST_URI'] !== null) {
            $this->uri = $_SERVER['REQUEST_URI'];
        }
        
        if ($method !== null) {
            $this->method = $method;
		} else if (isset($_POST['_method'])) {
			$this->method = $_POST['_method'];
        } else if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
			$this->method = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];
		} else if ($_SERVER['REQUEST_METHOD'] !== null) {
            $this->method = $_SERVER['REQUEST_METHOD'];
        }
		
		if(!\in_array($this->method, $this->allowedMethods)) {
			throw new Exception('HTTP method "' . $this->method . '" not allowed!');
		}
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
    public function get($key = null, $filter = true)
    {
        return $this->param($key, $filter, INPUT_GET);
    }
    
    /**
	 * Returns POST parameter $key. If no key is specified, the full POST 
	 * array is returned. Filtered by default.
	 * 
	 * @return array|string
	 */
    public function post($key = null, $filter = true)
    {
        return $this->param($key, $filter, INPUT_POST);
    }
    
    /**
	 * Filters request parameters using native PHP filters. If no key is 
	 * specified, the full array is returned.
	 * 
	 * @return array|string
	 */
    private function param($key, $filter, $type)
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
    }
	
	/**
	 * @return string
	 */
    public function __toString() {
        
    }
}