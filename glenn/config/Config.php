<?php
namespace glenn\config;

class Config
{
	protected $data = array();
	
	public function __construct(array $data)
	{
		foreach ($data as $key => $value) {
			if (is_array($value)) {
				$this->data[$key] = new self($value);
			} else {
				$this->data[$key] = $value;
			}
		}
	}
	
	public function __get($key)
	{
		if (\array_key_exists($key, $this->data)) {
			return $this->data[$key];
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
}