<?php
namespace glenn\router;

use glenn\http\Request;

/** Generalization of router. Only retrival of instance and matching URI to
*	route is supported. For configuration use concrete implementation.
*/
abstract class Router 
{

	/** Last created instance
	*/
	private static $currentRouter;

	/** Stores created instance, so it can be retrived with current().
	*	@param boolean $store True if store instance, else false.
	*/
	public function __construct($store = true) 
	{
		if ($store) {
			self::$currentRouter = $this;

		}
	}

	/** Find a matching route
	*	@param string $request_uri The URI used to accecss webpage ($_SERVER['REQUEST_URI'])
	*	@return array Array with indices 'controller' and 'action'.
	*	@throws Exception If no route found.
	*/
	abstract function resolveRoute(Request $request);

	/** Get current router, this should be the one that is specified by the
	*	user application.
	*	@return Router Last created router
	*	@throws Exception No router has been configured.
	*/
	public static function current() 
	{
		if (isset(self::$currentRouter)) {
			return self::$currentRouter;

		} else {
			throw new Exception('No router exist');

		}
	}

}
