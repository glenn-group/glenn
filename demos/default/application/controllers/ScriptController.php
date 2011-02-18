<?php
use Glenn\Dispatchable,
    Glenn\Request,
    Glenn\Response;

class ScriptController implements Dispatcher
{
    /*
     * @return Response
     */
	public function dispatch(Request $request)
	{
        try {
            return new Response("Script executed");
        } catch (Exception $e) {
            return new Response("Internal server error", 500);
        }
	}
}