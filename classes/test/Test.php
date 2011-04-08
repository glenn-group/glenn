<?php

namespace glenn\test;

abstract class Test {
	private $results;
	private $tests;
	private $code;
	
	function __construct()
	{
		$this->results = array();
		$this->tests = \array_filter(\get_class_methods($this), function($method) {
			return \strpos($method, 'test') === 0;
		});
	}

	
	/**
	 * Run all tests in class and return the result array.
	 *
	 * @return array
	 */
	public function run()
	{
		$this->setUp();
		
		// Run all tests
		foreach($this->tests as $test) {
			$name = \substr($test, \strlen('test'));
			$this->results[$test]['name'] = $name;
			$this->results[$test]['asserts'] = array();
			try {
				$this->$test();
				$this->results[$test]['result'] = 'pass';
			} catch (AssertException $ae) {
				$this->results[$test]['result'] = 'fail';
			}
		}
		
		$this->tearDown();
		
		return $this->results();
	}
	
	/**
	 * Return the number of tests in the class.
	 *
	 * @return int
	 */
	public function countTestCases()
	{
		return \count($this->tests);
	}
	
	/**
	 * Set up the fixture in preperation of running the tests.
	 */
	protected function setUp()
	{
	}
	
	/**
	 * Tear down the fixture after completing all tests.
	 */
	protected function tearDown()
	{
	}
	
	/**
	 * Return the result array which contains information on the execution of all tests and their
	 * asserts.
	 * 
	 * @return array
	 */
	public function results()
	{
		return $this->results;
	}
	
	protected function assertNotEqual($result, $other)
	{
		$this->assert($result, $other, false);
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
	
	protected function assert($data, $expected, $equality = true)
	{
		$trace = debug_backtrace();
		
		 // Remove this function from trace
		\array_shift($trace);
		
		// Get calling assert method
		$lastTrace = \array_shift($trace);
		$type = \substr($lastTrace['function'], \strlen('assert'));
		$line = $lastTrace['line'];
		if($this->code === null) {
			$this->code = file($lastTrace['file']);
		}
		$code = $this->code[$line-1];
		
		// Get calling test method
		$lastTrace = \array_shift($trace);
		$test = $lastTrace['function'];
		$result = $equality ? ($data === $expected) : ($data !== $expected);
		$data = $this->toString($data);
		$expected = $this->toString($expected);
		$this->results[$test]['asserts'][] = \compact('type', 'result', 'data', 'expected', 'line', 'code');

		// Throw assert error on failure to halt test execution
		if ($result !== true) {
			throw new AssertException();
		}
	}
	
	/**
	 * Format a value to a string value for printing.
	 * 
	 * @param $value
	 * @return string
	 */
	protected function toString($value)
	{
		if (\is_bool($value)) {
			return 'bool(' . ($value ? 'true' : 'false') . ')';
		} else if (\is_int($value)) {
			return 'int(' . $value . ')';
		} else if (\is_float($value)) {
			return 'float(' . $value . ')';
		} else if (\is_array($value)) {
			return 'array(' . count($value) . ')';
		} else if (\is_object($value)) {
			return 'object(' . \get_class($value) . ')';
		} else {
			return $value;
		}
	}
	
}