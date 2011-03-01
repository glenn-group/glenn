<?php
namespace glenn\config;

class Config
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
	
	public function parse(array $data)
	{
		$array = array();
		foreach ($data as $key => $value) {
			if (is_array($value)) {
				$array[$key] = new self($value);
			} else {
				$array[$key] = $value;
			}
		}
		return $array;
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
	
	public static function merge(Config $c1, Config $c2)
	{		
		$a1 = $c1->toArray();
		$a2 = $c2->toArray();
		foreach($a2 as $key => $value) {
			if (array_key_exists($key, $a1) && is_array($value)) {
				$a1[$key] = self::merge(new self($a1[$key]), new self($a2[$key]));
			} else {
				$a1[$key] = $value;
			}
		}
		return new self($a1);
	}
}