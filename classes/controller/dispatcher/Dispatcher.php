<?php
namespace glenn\controller\dispatcher;

use glenn\controller\Controller;
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
	protected static $actionSuffix = 'Action';
	
	/**
	 * Suffix for controller classes
	 * 
	 * @var string
	 */
	protected static $controllerSuffix = 'Controller';
	
	/**
	 * Stack of EventHandlers
	 */
	protected static $events = array();
	
	/**
	 * Dispatch a request
	 * 
	 * @param  Request  $request 
	 * @return Response
	 */
	public static function dispatch(Request $request)
	{
		// Push a new EventHandler to the top of the stack
		static::pushEventHandler();
		
		if (static::isExternal($request)) {
			$response = static::dispatchExternal($request);
		} else {
			$response = static::dispatchInternal($request);
		}
		
		// Pull EventHandler from the top of the stack
		static::pullEventHandler();
		
		return $response;
	}
	
	/**
	 * Retrieve active EventHandler
	 */
	public static function events()
	{
		return static::$events[\count(static::$events) - 1];
	}
	
	/**
	 * Dispatch an external request over a socket
	 * 
	 * @param  Request  $request 
	 * @return Response
	 */
	protected static function dispatchExternal($request)
	{
		// Open up a socket connection to the host
		$fp = \fsockopen('www.google.com', 80);
		
		$out = "GET / HTTP/1.1\r\n";
		$out.= "Host: www.google.com\r\n";
		$out.= "Connection: Close\r\n\r\n";
		\fwrite($fp, $out);
		
		
		while (!\feof($fp)) {
			echo \fgets($fp);
			echo '<br/>';
		}
		
		// Close socket connection before returning
		\fclose($fp);
		
		exit;
		/*
		$body = \file_get_contents($request->uri());
		
		foreach ($http_response_header as $header) {
			if (\strpos($header, 'HTTP') === 0) {
				$status   = \substr($header, \strpos($header, ' ') + 1, 3);
				$response = new Response($body, $status);
				continue;
			}
			$response->addHeader(
				\substr($header, 0, \strpos($header, ':')), 
				\substr($header, \strpos($header, ':') + 1)
			);
		}
		
		return $response;
		*/
	}
	
	/**
	 * Dispatch an internal request
	 * 
	 * @param  Request  $request 
	 * @return Response
	 */
	protected static function dispatchInternal($request)
	{
		// Instantiate action controller
		$controller = Controller::factory(
			static::formatControllerName($request->controller), $request
		);
		
		// Trigger dispatch before event
		$responses = static::triggerUntilResponse(
			static::events(), new Event($controller, 'glenn.dispatch.before')
		);
		if (\count($responses) > 0) {
			return $responses[0];
		}
		
		// Execute controller action
		$response = \call_user_func(array(
			$controller, static::formatActionName($request->action)
		));
		if ($response instanceof Response) {
			return $response;
		}
		
		// Trigger dispatch after event
		$responses = static::triggerUntilResponse(
			static::events(), new Event($controller, 'glenn.dispatch.after')
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
	
	protected static function formatControllerName($unformatted)
	{
		return 'app\\controller\\' . \ucfirst($unformatted) . static::$controllerSuffix;
	}
	
	protected static function formatActionName($unformatted)
	{
		return \lcfirst($unformatted) . static::$actionSuffix;
	}
	
	protected static function isExternal($request)
	{
		return \strpos($request->uri(), 'http://') === 0;
	}
	
	protected static function pushEventHandler()
	{
		\array_push(static::$events, new EventHandler());
	}
	
	protected static function pullEventHandler()
	{
		\array_pop(static::$events);
	}
	
	protected static function triggerUntilResponse(EventHandler $events, Event $event)
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