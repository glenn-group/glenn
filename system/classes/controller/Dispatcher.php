<?php
namespace glenn\controller;

use glenn\http\Request;

interface Dispatcher
{
	/**
	 * @return Response
	 */
	public function dispatch(Request $request);
}