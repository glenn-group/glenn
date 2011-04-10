<?php

namespace glenn\test;

abstract class Test {
	private $results;
	private $tests;
	private $currentTest;
	
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
			$this->currentTest = $test;
			$this->results[$test]['name'] = \substr($test, \strlen('test'));
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
	
	/**
	 * Call one assertion method multiple times, one for each item in the data array.
	 *
	 * @param string $assert
	 * @param array $data 
	 */
	protected function multiAssert($assert, array $data)
	{
		$method = 'assert'.\ucfirst($assert);
		if (!\method_exists($this, $method)) {
			throw new \BadMethodCallException("No method $method exists.");
		}
		
		foreach($data as $item) {
			if(!\is_array($item)) {
				$item = array($item);
			}
			\call_user_func_array(array($this, $method), $item);
		}
	}

	/*
	 * ASSERTION METHODS
	 */

	protected function assertNotEqual($value, $other)
	{
		// Assertion with closure that returns both assertion status and message.
		$this->assert($value, $other, function($val, $val2) {
			return array(
				'status' => $val !== $val2,
				'message' => '{value} must not be equal to {other}.'
			);
		});
	}
	
	protected function assertEqual($value, $other)
	{
		// Assertion with boolean result and custom message.
		$this->assert($value, $other, $value === $other, '{value} must equal {other}.');
	}
	
	protected function assertFalse($value)
	{
		// Assertion with closure that only returns assertion status and with message passed 
		// via assert parameter.
		$this->assert($value, false, function($val, $val2) {
			return $val === $val2;
		}, '{value} must be false.');
	}
	
	protected function assertTrue($value)
	{
		$this->assert($value, true, $value === true, '{value} must be true.');
	}
	
	protected function assertInArray($value, $array)
	{
		$this->assert($value, $array, \in_array($value, $array));
	}
	
	/**
	 * Main assertion method of which all other assertions are only for convenience.
	 *
	 * @param mixed $value
	 * @param mixed $other
	 * @param boolean|closure $result
	 * @param string $message
	 */
	protected function assert($value, $other, $result, $message = '')
	{
		// If result is a closure it must be called and its result evaluated
		if (\is_callable($result)) {
			$called = call_user_func_array($result, compact('value', 'other'));
			if (\is_array($called)) {
				extract($called, EXTR_OVERWRITE);
			} else {
				$status = $called;
			}
		} else {
			$status = $result;
		}
		
		// Replace keywords with method parameter data
		$message = str_replace(
				array('{value}', '{other}'), 
				array($this->toString($value), $this->toString($other)),
				$message
		);
		
		// Store assertion result
		$this->results[$this->currentTest]['asserts'][] = \compact('status', 'message');

		// Throw assert error on failure to halt test execution
		if ($status !== true) {
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