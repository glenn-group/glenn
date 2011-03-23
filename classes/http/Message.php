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
		if (\array_key_exists($key, $this->headers)) {
			if (\is_array($this->headers[$key])) {
				$this->headers[$key][] = $value;
			} else {
				$this->headers[$key] = array(
					$this->headers[$key], $value
				);
			}
		} else {
			$this->headers[$key] = $value;
		}
	}
	
	/**
	 * @return array
	 */
	public function headers()
	{
		return $this->headers;
	}
	
	/**
	 * @param type $protocol 
	 */
	public function setProtocol($protocol)
	{
		$this->protocol = $protocol;
	}
}