<?php
namespace glenn\controller\dispatcher;

use glenn\controller\Dispatcher,
    glenn\http\Request,
	glenn\http\Response;

class ExternalDispatcher implements Dispatcher
{
	/**
     *
     * @param  Request $request
     * @return Response 
     */
	public function dispatch(Request $request)
	{
		$body = \file_get_contents($request->uri());
		
		$headers = array();
		foreach ($http_response_header as $header) {
			if (\strpos($header, 'HTTP') === 0) {
				$status  = \substr(
					$header, \strpos($header, ' ') + 1, 3
				);
				$response = new Response($body, $status);
				continue;
			}
			$response->addHeader(
				\substr($header, 0, \strpos($header, ':')), 
				\substr($header, \strpos($header, ':') + 1)
			);
		}
		
		return $response;
	}
}