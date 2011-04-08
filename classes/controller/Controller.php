<?php
namespace glenn\controller;

use glenn\event\Event;
use glenn\event\EventHandler;
use glenn\http\Request;
use glenn\http\Response;
use glenn\view\View;

abstract class Controller
{
	/**
	 * After filters
	 * 
	 * @var array
	 */
	protected $after = array();
	
	/**
	 * Before filters
	 * 
	 * @var array
	 */
	protected $before = array();
    
	/**
	 * @var EventHandler
	 */
	protected $events;
	
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
	 * @param Request request
	 */
	public function __construct(Request $request)
	{
		$this->events  = new EventHandler();
		$this->request = $request;
		
		// Create a view with some sane defaults
		$this->events->bind('glenn.action.before', function(Event $e) {
			$e->subject()->setView(new View(
				$e->param('controller') . '/' . $e->param('action')
			));
		});
		
		// Bind filters to be triggered before dispatch
		$this->bindFilters($this->before, 'glenn.action.before');
		
		// Automagically set a response after dispatch
		$this->events->bind('glenn.action.after', function(Event $e) {
			if ($e->subject()->response() === null) {
				$e->subject()->setResponse(new Response(
					$e->subject()->view()->render()
				));
			}
		});
		
		// Bind filters to be triggered after dispatch
		$this->bindFilters($this->after, 'glenn.action.after');
	}
	
	/**
	 * @return EventHandler
	 */
	public function events()
	{
		return $this->events;
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
	
	/**
	 * Bind filters on this controller's EventHandler. A filter is a 
	 * public method on the subject of the event (this controller).
	 * 
	 * @param array  $filters Array of filters to be bound
	 * @param string $event   Event to bind filters to
	 */
	protected function bindFilters($filters, $event)
	{
		foreach ($filters as $key => $value) {
			$this->events->bind($event, function(Event $e) use ($key, $value) {
				if (\is_array($value)) {
					if (!\in_array($e->param('action'), $value)) {
						return false;
					}
					return \call_user_func(array($e->subject(), $key));
				} else {
					return \call_user_func(array($e->subject(), $value));
				}
			});
		}
	}
}