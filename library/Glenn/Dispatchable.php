<?php
namespace Glenn;

interface Dispatchable
{
	/*
	 * @return Response
	 */
	public function dispatch(Request $request);
}