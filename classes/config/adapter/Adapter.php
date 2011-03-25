<?php
namespace glenn\config\adapter;

abstract class Adapter implements \IteratorAggregate
{
	protected $data = array();
	
	public function __construct(array $data)
	{
		$this->data = $this->parse($data);
	}
	
	public function __get($key)
	{
		if (\array_key_exists($key, $this->data)) {
			return $this->data[$key];
		}
	}
	
	public function __isset($key)
	{
		return isset($this->data[$key]);
	}
	
	public abstract function parse(array $data);
	
	public function merge(Config $config)
	{
		foreach ($config as $key => $value) {
			if (isset($this->data[$key]) && 
				$this->data[$key] instanceof self && $value instanceof self) {
				$this->data[$key]->merge($value);
			} else {
				$this->data[$key] = $value;
			}
		}
	}
	
	public function toArray()
	{
		$array = array();
		foreach ($this->data as $key => $value) {
			if ($value instanceof self) {
				$array[$key] = $value->toArray();
			} else {
				$array[$key] = $value;
			}
		}
		return $array;
	}
	
	public function getIterator()
	{
		return new \ArrayIterator($this->data);
	}
}