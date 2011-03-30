<?php
namespace glenn\controller\dispatcher;

use glenn\controller\Dispatcher;
use glenn\controller\Controller;
use glenn\event\Event;
use glenn\event\EventHandler;
use glenn\http\Request;
use glenn\http\Response;
use glenn\router\Router;

class InternalDispatcher implements Dispatcher
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
	 * The dispatcher's event handler
	 * 
     * @var EventHandler
     */
    protected $events;
    
    /**
	 * The dispatcher's router
	 * 
     * @var Router
     */
	protected $router;
	
    /**
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->events = new EventHandler();
        $this->router = $router;
    }
    
    /**
     *
     * @param  Request  $request
     * @return Response 
     */
	public function dispatch(Request $request)
	{
		/*
		if (\strpos($request->uri(), 'http://') === 0) {
			$dispatcher = new ExternalDispatcher();
			return $dispatcher->dispatch($request);
		}
		*/
 
		/*
        try {
            $this->routerResult = new \stdClass();
            $this->routerResult->controller = 'app\\controller\\indexController';
            $this->routerResult->action     = 'indexAction';
        } catch (Exception $e) {
            return Response::notFound();
        }
		*/
		
		$request->controller = 'index';
		$request->action = 'index';

		$controller = Controller::factory(
			$this->formatControllerName($request->controller), $this, $request
		);
		
		// If a callback returns a valid response, return it
		$responses = $this->events->triggerUntil(
			new Event($controller, 'glenn.dispatching.before'),
			function($response) {
				return $response instanceof Response;
			},
			function ($response) {
				return $response instanceof Response;
			}
		);
		if (\count($responses) > 0) {
			return $responses[0];
		}
		
		// Excecute controller action and return response if valid
		$response = \call_user_func(array(
			$controller, $this->formatActionName($request->action)
		));
		if ($response instanceof Response) {
			return $response;
		}
		
		// If a callback returns a valid response, return it
		$responses = $this->events->triggerUntil(
			new Event($controller, 'glenn.dispatching.after'),
			function($response) {
				return $response instanceof Response;
			},
			function ($response) {
				return $response instanceof Response;
			}
		);
		if (\count($responses) > 0) {
			return $responses[0];
		}
		
		// Return controller response if it exists and is valid
		$response = $controller->response();
		if ($response instanceof Response) {
			return $response;
		}
		
		// No valid response was ever returned, time to throw an exception
		throw new \Exception('No response returned');
	}
	
    /**
     *
     * @return EventHandler
     */
    public function events() 
    {
        return $this->events;
    }
    
    /**
     *
     * @return Router
     */
	public function router()
	{
		return $this->router;
	}
	
	
    
    
    
    
    
    /**
	 * @param  string $unformatted
	 * @return string
	 */
    protected function formatControllerName($unformatted)
	{
		return 'app\\controller\\' . \ucfirst($unformatted) . $this->controllerSuffix;
	}
	
	/**
	 * @param  string $unformatted
	 * @return string
	 */
	protected function formatActionName($unformatted)
	{
		return \lcfirst($unformatted) . $this->actionSuffix;
	}
}
