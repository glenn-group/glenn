<?php

namespace glenn\test;

abstract class Test {
	private $results = array();
	
	/**
	 *
	 * @return type 
	 */
	public function run()
	{
		$tests = array_filter(\get_class_methods($this), function($method) {
			return \strpos($method, 'test') === 0;
		});
		foreach($tests as $test) {
			$name = \substr($test, \strlen('test'));
			try {
				$this->$test();
				$this->results[$test] = $this->results[$test] + array(
					'name' => $name,
					'result' => 'pass'
				);
			} catch (AssertException $ae) {
				$this->results[$test] = $this->results[$test] + array(
						'name' => $name,
						'result' => 'fail'
				);
			}
		}
		return $this->results();
	}
	
	public function results()
	{
		return $this->results;
	}
	
	protected function assertNotEqual($result, $other)
	{
		$this->assert($result === $other, false);
	}
	
	protected function assertEqual($result, $other)
	{
		$this->assert($result, $other);
	}
	
	protected function assertFalse($result)
	{
		$this->assert($result, false);
	}
	
	protected function assertTrue($result)
	{
		$this->assert($result, true);
	}
	
	protected function assert($data, $expected)
	{
		$trace = debug_backtrace();
		\array_shift($trace);
		$lastTrace = \array_shift($trace);
		$type = \substr($lastTrace['function'], \strlen('assert'));
		$lastTrace = \array_shift($trace);
		$test = $lastTrace['function'];
		$result = ($data === $expected);
		$this->results[$test]['asserts'][] = \compact('type', 'result', 'data', 'expected');

		if ($result !== true) {
			throw new AssertException();
		}
	}
	
}