<?php
namespace glenn\controller;

use glenn\event\Event;
use glenn\event\EventHandler;
use glenn\http\Request;
use glenn\http\Response;
use glenn\router\Router;

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
	 * The dispatcher's EventHandler
	 * 
	 * @var EventHandler
	 */
	protected $events;
	
	/**
	 * The dispatcher's Router
	 * 
	 * @var Router
	 */
	protected $router;
	
	/**
	 * 
	 * 
	 * @param Router $router
	 */
	public function __construct(Router $router)
	{
		$this->events = new EventHandler();
		$this->router = $router;
	}
	
	/**
	 * Dispatch a request internally or externally
	 * 
	 * @param  Request  $request 
	 * @return Response
	 */
	public function dispatch(Request $request)
	{
		if ($this->isExternal($request)) {
			return $this->dispatchExternal($request);
		} else {
			return $this->dispatchInternal($request);
		}
	}
	
	/**
	 * Retrieve active EventHandler
	 */
	public function events()
	{
		return $this->events;
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
		switch($request->scheme()) {
			case 'http://':
				$fp = \fsockopen($request->hostname(), 80); 
				break;
			case 'https://':
				$fp = \fsockopen('ssl://' . $request->hostname(), 443); 
				break;
		}
		
		// Make sure the connection is not kept alive
		$request->addHeader('Connection', 'Close');
		
		// Write request to socket
		\fwrite($fp, $request);
		
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
		$result = $this->router->resolveRoute($request);
		$request->controller = $result['controller'];
		$request->action     = $result['action'];
		
		//
		$responses = $this->triggerUntilResponse(
			$this->events(), new Event($this, 'glenn.dispatch.before', array(
				'request'    => $request,
				'controller' => $request->controller,
				'action'     => $request->action
			))
		);
		if (\count($responses) > 0) {
			return $responses[0];
		}
		
		// Instantiate action controller
		$controller = Controller::factory(
			$this->formatControllerName($request->controller), $request
		);
		
		// 
		$responses = $this->triggerUntilResponse(
			$controller->events(), new Event($controller, 'glenn.action.before', array(
				'request'    => $request,
				'controller' => $request->controller,
				'action'     => $request->action
			))
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
		
		// 
		$responses = $this->triggerUntilResponse(
			$controller->events(), new Event($controller, 'glenn.action.after', array(
				'request'    => $request,
				'controller' => $request->controller,
				'action'     => $request->action
			))
		);
		if (\count($responses) > 0) {
			return $responses[0];
		}
		
		//
		$responses = $this->triggerUntilResponse(
			$this->events(), new Event($this, 'glenn.dispatch.after', array(
				'request'    => $request,
				'controller' => $request->controller,
				'action'     => $request->action
			))
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
	
	/**
	 * Format controller name into a proper class name
	 * 
	 * @param  string $unformatted
	 * @return string
	 */
	protected function formatControllerName($unformatted)
	{
		return 'app\\controller\\' . \ucfirst($unformatted) . $this->controllerSuffix;
	}
	
	/**
	 * Format action name into a proper method name
	 * 
	 * @param  string $unformatted
	 * @return string
	 */
	protected function formatActionName($unformatted)
	{
		return \lcfirst($unformatted) . $this->actionSuffix;
	}
	
	/**
	 * Determine if a request is internal or external
	 * 
	 * @param  Request $request
	 * @return boolean
	 */
	protected function isExternal($request)
	{
		return ($request->scheme() === 'http://' || $request->scheme() === 'https://');
	}
	
	/**
	 * 
	 * 
	 * @param  EventHandler $events
	 * @param  Event        $event
	 * @return array
	 */
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