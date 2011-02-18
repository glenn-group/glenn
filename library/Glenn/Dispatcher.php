<?php
namespace Glenn;

interface Dispatcher
{
	/*
	 * @return Response
	 */
	public function dispatch(Request $request);
}