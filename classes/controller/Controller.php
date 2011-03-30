<?php
namespace glenn\controller;

use glenn\event\Event;
use glenn\http\Request;
use glenn\http\Response;
use glenn\view\View;

abstract class Controller
{
	/**
	 * Registered after filters
	 * 
	 * @var array
	 */
	protected $after = array();
	
	/**
	 * Registered before filters
	 * 
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
	 * @var Response
	 */
	protected $response;
	
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
			throw new \Exception("Class $class not instance of Controller");
		}
		return $controller;
    }
    
	/**
	 * @param Dispatcher $dispatcher
	 * @param Request    $request
	 */
    public function __construct(Dispatcher $dispatcher, Request $request)
    {
        //$this->dispatcher = $dispatcher;
        $this->request    = $request;
		//$this->view       = new View();
		
		/*
		// Trigger before filters
		foreach ($this->before as $before) {
			$this->dispatcher()->events()->bind(
				'glenn.dispatching.before', function(Event $e) use($before) {
					return \call_user_func(array($e->subject(), $before));
				}
			);
		}
		
		// Automagically create a response from the controller's view object
		$this->dispatcher()->events()->bind('glenn.dispatching.after', function(Event $e) {
			$e->subject()->view()->setTemplate(
				$e->subject()->request()->controller . '/' .
				$e->subject()->request()->action
			);
			$e->subject()->setResponse(new Response($e->subject()->view()));
		});
		
		// Trigger after filters
		foreach ($this->after as $after) {
			$this->dispatcher()->events()->bind(
				'glenn.dispatching.after', function(Event $e) use($after) {
					return \call_user_func(array($e->subject(), $after));
				}
			);
		}
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
	 * @return Response
	 */
	public function response()
	{
		return $this->response;
	}
	
	/**
	 * @param Response $response 
	 */
	public function setResponse(Response $response)
	{
		$this->response = $response;
	}
	
	/**
	 * @return View
	 */
	public function view()
	{
		return $this->view;
	}
	
	/**
	 * @param View $view
	 */
	public function setView(View $view)
	{
		$this->view = $view;
	}
}