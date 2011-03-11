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
	
	public function params()
	{
		return $this->params;
	}
}