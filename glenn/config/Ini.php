<?php
namespace glenn\config;

class Ini extends Config
{
	public function __construct($filename)
	{
		parent::__construct($this->parse($filename));
	}
	
	private function parse($filename)
	{
		$array = \parse_ini_file($filename, true);
		return $array;
	}
}