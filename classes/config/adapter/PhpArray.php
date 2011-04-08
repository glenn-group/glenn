<?php
namespace glenn\config\adapter;

class PhpArray extends Adapter
{
	
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

}