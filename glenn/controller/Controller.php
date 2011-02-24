<?php
namespace glenn\controller;

use glenn\http\Request;

abstract class Controller
{
    protected $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
	
	public function request()
	{
		return $this->request;
	}
}