<?php

namespace glenn\test;

class Controller extends \glenn\controller\Controller
{
	
	public function index()
	{
		$test = new ExampleTest();
		$this->view()->set('tests', $test->countTestCases());
		$result = $test->run();
		$this->view()->set('results', $result);
	}
	
}
