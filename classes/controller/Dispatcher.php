<?php
namespace glenn\controller;

use glenn\event\Event;
use glenn\event\EventHandler;
use glenn\http\Request;
use glenn\http\Response;

class Dispatcher
{
	/**
	 * Suffix for action methods
	 * 
	 * @var string
	 */
	protected $actionSuffix = 'Action';
	
	/**
	 * Suffix for controller classes
	 * 
	 * @var string
	 */
	protected $controllerSuffix = 'Controller';
	
	/**
	 * Stack of EventHandlers
	 */
	protected $events = array();
	
	/**
	 * Dispatch a request
	 * 
	 * @param  Request  $request 
	 * @return Response
	 */
	public function dispatch(Request $request)
	{
		// Push a new EventHandler to the top of the stack
		$this->pushEventHandler();
		
		if ($this->isExternal($request)) {
			$response = $this->dispatchExternal($request);
		} else {
			$response = $this->dispatchInternal($request);
		}
		
		// Pull EventHandler from the top of the stack
		$this->pullEventHandler();
		
		return $response;
	}
	
	/**
	 * Retrieve active EventHandler
	 */
	public function events()
	{
		return $this->events[\count($this->events) - 1];
	}
	
	/**
	 * Dispatch an external request over a socket
	 * 
	 * @param  Request  $request 
	 * @return Response
	 */
	protected function dispatchExternal($request)
	{
		// Open up a socket to the host
		$fp = \fsockopen($request->hostname(), 80);
		
		// Make sure the connection is not kept alive
		$request->addHeader('Connection', 'Close');
		
		// Write request to socket
		\fwrite($fp, $request->__toString());
		
		// Read response from and close socket
		$response = '';
		while (!\feof($fp)) {
			$response .= \fgets($fp);
		}
		\fclose($fp);
		
		// Return response object created from response string
		return Response::fromString($response);
	}
	
	/**
	 * Dispatch an internal request
	 * 
	 * @param  Request  $request 
	 * @return Response
	 */
	protected function dispatchInternal($request)
	{
		// Instantiate action controller
		$controller = Controller::factory(
			$this->formatControllerName($request->controller), $request
		);
		
		// Trigger dispatch before event
		$responses = $this->triggerUntilResponse(
			$this->events(), new Event($controller, 'glenn.dispatch.before')
		);
		if (\count($responses) > 0) {
			return $responses[0];
		}
		
		// Execute controller action
		$response = \call_user_func(array(
			$controller, $this->formatActionName($request->action)
		));
		if ($response instanceof Response) {
			return $response;
		}
		
		// Trigger dispatch after event
		$responses = $this->triggerUntilResponse(
			$this->events(), new Event($controller, 'glenn.dispatch.after')
		);
		if (\count($responses) > 0) {
			return $responses[0];
		}
		
		// Return controller response
		$response = $controller->response();
		if ($response instanceof Response) {
			return $response;
		}
		
		throw new \Exception('No response returned');
	}
	
	protected function formatControllerName($unformatted)
	{
		return 'app\\controller\\' . \ucfirst($unformatted) . $this->controllerSuffix;
	}
	
	protected function formatActionName($unformatted)
	{
		return \lcfirst($unformatted) . $this->actionSuffix;
	}
	
	protected function isExternal($request)
	{
		return \strpos($request->uri(), 'http://') === 0;
	}
	
	protected function pushEventHandler()
	{
		\array_push($this->events, new EventHandler());
	}
	
	protected function pullEventHandler()
	{
		\array_pop($this->events);
	}
	
	protected function triggerUntilResponse(EventHandler $events, Event $event)
	{
		return $events->triggerUntil(
			$event,
			function($response) {
				return $response instanceof Response;
			},
			function ($response) {
				return $response instanceof Response;
			}
		);
	}
}