<?php
namespace glenn\controller\dispatcher;

use glenn\controller\Dispatcher,
	glenn\event\Event,
	glenn\event\EventHandler,
    glenn\http\Request,
	glenn\http\Response;

class CallbackDispatcher implements Dispatcher
{
	/**
     * @var EventHandler
     */
    protected $events;
	
	/**
     *
     */
    public function __construct()
    {
        $this->events = new EventHandler();
    }
	
	public function bind($route, $callback)
	{
		$this->events->bind($route, $callback);
	}
	
	public function dispatch(Request $request)
	{
		$route = $this->router->match($request);
		
		$this->events->triggerUntil(
			new Event($this, $route),
			function($response) {
				return $response instanceof Response;
			},
			function($response) {
				return $response instanceof Response;
			}
		);
		if (\count($responses) > 0) {
			return $responses[0];
		}
	}
}

$dispatcher = new CallbackDispatcher();

$dispatcher->bind('/', function() {
	return new Response('Hello world');
});

$dispatcher->bind('/foo', function() {
	switch ($request->method()) {
		case 'GET':
			return new Response('Requested by GET');
			break;
		case 'POST':
			return new Response('Requested by POST');
			break;
	}
});

$response = $dispatcher->dispatch($request);
$response->send();