<?php
namespace glenn\controller;

use glenn\http\interfaces\Request;

interface Dispatcher
{
	/**
	 * @return Response
	 */
	public function dispatch(Request $request);
}