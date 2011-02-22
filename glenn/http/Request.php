<?php
namespace glenn\http;

class Request extends Message
{
    protected $method;
	
	protected $uri;
    
	public function __construct($uri = null, $method = null)
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
    }
	
	public function method()
	{
		return $this->method;
	}
	
	public function uri()
	{
		return $this->uri;
	}
	
    public function __toString() {
        
    }
}