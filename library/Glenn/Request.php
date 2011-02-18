<?php
namespace Glenn;

class Request
{
    public $uri;
    public $method;
    	
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
    
    public function __toString() {
        
    }
}

