<?php
namespace glenn\activerecord;

class ActiveRecord
{
	public $db;
	
	public $data;
	
	public function __construct(array $data)
	{
		$this->data = $data;
	}
	
	public function save()
	{
		
	}
	
	public function delete()
	{
		
	}
	
	public function toArray()
	{
		return $this->data;
	}
}