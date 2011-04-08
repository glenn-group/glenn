<?php

namespace glenn\http\interfaces;

interface Request
{
	
	/**
	 * Return the HTTP method of the request.
	 * 
	 * @return string
	 */
	public function method();
	
	/**
	 * Return the URI string of the request.
	 * 
	 * @return string
	 */
	public function uri();
	
	/**
	 * Returns GET parameter $key. If no key is specified, the full GET 
	 * array is returned. Filtered by default.
	 * 
	 * @return array|string
	 */
    public function get($key = null, $filter = true);
    
    /**
	 * Returns POST parameter $key. If no key is specified, the full POST 
	 * array is returned. Filtered by default.
	 * 
	 * @return array|string
	 */
    public function post($key = null, $filter = true);
    
}