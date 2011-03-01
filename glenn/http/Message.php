<?php
namespace glenn\http;

class Message
{
	/**
	 * @var array 
	 */
	protected $headers = array();
	
	/**
	 * @var string
	 */
    protected $protocol = 'HTTP/1.1';
	
	/**
	 * @param string $key
	 * @param string $value 
	 */
	public function addHeader($key, $value)
	{
		$this->headers[$key] = $value;
	}
}