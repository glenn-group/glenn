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
		if (\function_exists('curl_init') === false) {
			return $this->curl($request);
		} else {
			return $this->fileGetContents($request);
		}
	}
	
	private function curl(Request $request)
	{
		$ch = \curl_init();
		\curl_setopt($ch, \CURLOPT_URL, $request->uri());
		\curl_setopt($ch, \CURLOPT_HEADER, true);
		switch ($request->method()) {
			case 'DELETE':
				\curl_setopt($ch, \CURLOPT_POST, true);
				break;
			case 'HEAD':
				\curl_setopt($ch, \CURLOPT_NOBODY, true);
				break;
			case 'PUT':
				\curl_setopt($ch, \CURLOPT_PUT, true);
				break;
			case 'POST':
				\curl_setopt($ch, \CURLOPT_POST, true);
				break;
		}
		\curl_exec($ch);
		\curl_close($ch);
	}
	
	private function fileGetContents(Request $request)
	{
		$body = \file_get_contents($request->uri());
		
		foreach ($http_response_header as $header) {
			if (\strpos($header, 'HTTP') === 0) {
				$status   = \substr($header, \strpos($header, ' ') + 1, 3);
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