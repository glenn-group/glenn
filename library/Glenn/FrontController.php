<?php
namespace Glenn;

class FrontController implements Dispatcher
{
    private $router;
    
    public function __construct() 
    {
        $this->router = new Router();
    }
    
	public function dispatch(Request $request)
	{
        $result = $this->router->route($request);
        
        $class = ucfirst($result['controller']) . 'Controller';
        $method = lcfirst($result['action']) . 'Action';
        
        //if ($class instanceof Controller) {
            $controller = new $class;
        //} else {
        //    throw new Exception("");
        //}
        
        //$controller->setRequest($request);
        
        $controller->$method();
        
        // perhaps controller could dispatch action?
        //$controller->dispatch($modifiedRequest);
        
        return new Response("Page not found", 404);
	}
    
    public function getRouter()
    {
        return $this->router;
    }
}