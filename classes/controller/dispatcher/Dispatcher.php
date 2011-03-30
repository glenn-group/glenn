<?php
namespace glenn\controller\dispatcher;

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
        static::pushEventHandler();
        
        if (static::isExternal($request)) {
            $response = static::dispatchExternal($request);
        } else {
            $response = static::dispatchInternal($request);
        }
        
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
	 * Dispatch an external request
	 * 
	 * @param  Request  $request 
	 * @return Response
	 */
	protected static function dispatchExternal($request)
    {
        
    }
    
	/**
	 * Dispatch an internal request
	 * 
	 * @param  Request  $request 
	 * @return Response
	 */
    protected static function dispatchInternal($request)
    {
        $request->controller = 'index';
		$request->action     = 'index';
		
		$controller = Controller::factory(
			$this->formatControllerName($request->controller), $request
		);
		
		$response = \call_user_func(array(
			$controller, static::formatActionName($request->action)
		));
		if ($response instanceof Response) {
			return $response;
		}
		
		// No valid response was ever returned, time to throw an exception
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
}