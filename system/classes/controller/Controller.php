<?php
namespace glenn\controller;

use glenn\http\Request,
    glenn\view\View;

abstract class Controller
{
    protected $request;
	
	protected $view;
    
    public function __construct(Request $request, View $view)
    {
        $this->request = $request;
		$this->view    = $view;
    }
	
	public function request()
	{
		return $this->request;
	}
	
	public function view()
	{
		return $this->view;
	}
}