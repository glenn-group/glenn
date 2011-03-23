<?php
namespace glenn\event;

class Event
{
	protected $subject;
	
	protected $name;
	
	protected $params;
	
	public function __construct($subject, $name, $params = array())
	{
		$this->subject = $subject;
		$this->name    = $name;
		$this->params  = $params;
	}
	
	public function subject()
	{
		return $this->subject;
	}
	
	public function name()
	{
		return $this->name;
	}
	
	public function param($key)
	{
		if (\array_key_exists($key, $this->params)) {
			return $this->params[$key];
		}
		throw new \Exception('No such parameter');
	}
	
	public function params()
	{
		return $this->params;
	}
}