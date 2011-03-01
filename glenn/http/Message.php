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
}