<?php

namespace glenn\test;

class Controller extends \glenn\controller\Controller
{
	
	public function index()
	{
		$test = new ExampleTest();
		$result = $test->run();
		$this->view()->set('results', $result);
	}
	
}
