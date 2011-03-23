<?php
namespace glenn\controller;

use glenn\event\Event,
    glenn\http\Request,
    glenn\view\View;

abstract class Controller
{
	/**
	 * @var array
	 */
	protected $after = array();
	
	/**
	 * @var array
	 */
	protected $before = array();
	
	/**
	 * @var Dispatcher
	 */
    protected $dispatcher;
    
	/**
	 * @var Request
	 */
    protected $request;
	
	/**
	 * @var View
	 */
	protected $view;
    
	/**
	 * @param  string     $class
	 * @param  Dispatcher $dispatcher
	 * @param  Request    $request
	 * @return Controller
	 */
    public static function factory($class, Dispatcher $dispatcher, Request $request)
    {
		if (!class_exists($class)) {
			throw new \Exception("Class $class does not exist");
		}
		$controller = new $class($dispatcher, $request);
		if (!$controller instanceof self) {
			throw new \Exception("Class $class is not an instance of Controller");
		}
		return $controller;
    }
    
	/**
	 * @param Dispatcher $dispatcher
	 * @param Request    $request
	 */
    public function __construct(Dispatcher $dispatcher, Request $request)
    {
        $this->dispatcher = $dispatcher;
        $this->request    = $request;
		
		/*
		$this->dispatcher()->events()->bind('glenn:dispatching:before', function(Event $e) {
			
		});
		
		$this->dispatcher()->events()->bind('glenn:dispatching:after', function(Event $e) {
			
		});
		 */
    }
	
	/**
	 * @return Dispatcher
	 */
	public function dispatcher()
	{
		return $this->dispatcher;
	}
	
	/**
	 * @return Request
	 */
	public function request()
	{
		return $this->request;
	}
	
	/**
	 * @return View
	 */
	public function view()
	{
		return $this->view;
	}
}