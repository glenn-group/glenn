<?php
namespace glenn\event;

class EventHandler
{
	private $events = array();
	
	/**
	 * Bind a callback function to a named event.
	 *
	 * @param string   $name     Event name
	 * @param Callable $callback Callback function to execute
	 */
	public function bind($name, $callback)
	{
		if (\is_callable($callback)) {
			$this->events[$name][] = $callback;
		}
	}
	
	/**
	 * Trigger the complete event chain.
	 * 
	 * @param  Event    $e      The event to trigger
	 * @param  Callable $filter Add response if callback evaluates to true
	 * @return array            Results returned by executed callback functions
	 */
	public function trigger(Event $e, $filter = null)
	{
		return $this->triggerUntil($e, $filter, function() {
			return false;
		});
	}
	
	/**
	 * Trigger the event chain until a callback function evaluates to true.
	 * 
	 * @param  Event    $e      The event to trigger
	 * @param  Callable $filter Add response if callback evaluates to true
	 * @param  Callable $break  Stop propagation if callback evaluates to true
	 * @return array            Results returned by executed callback functions 
	 */
	public function triggerUntil(Event $e, $filter = null, $break = null)
	{
		// Default filter callback function
		if ($filter === null) {
			$filter = function($response) {
				return true;
			};
		} else if (!is_callable($filter)) {
			throw new \Exception('Invalid callback provided');
		}
		
		// Default break callback function
		if ($break === null) {
			$break = function($response) {
				if ($response === true) {
					return true;
				}
			};
		} else if (!is_callable($break)) {
			throw new \Exception('Invalid callback provided');
		}
		
		// Make sure the event has registered callbacks before proceeding
		if (!array_key_exists($e->name(), $this->events)) {
			return;
		}
		
		$responses = array();
		
		// Iterate over registered callbacks
		foreach ($this->events[$e->name()] as $callback) {
			
			// Execute callback function with triggered event as parameter
			$response = \call_user_func($callback, $e);
			
			// Add response if callback evaluates to true
			if (\call_user_func($filter, $response)) {
				$responses[] = $response;
			}
			
			// Stop propagation if callback evaluates to true
			if (\call_user_func($break, $response)) {
				break;
			}
		}
		
		// Return callback responses in array form
		return $responses;
	}
}