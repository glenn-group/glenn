<?php

namespace glenn\test;

class Controller extends \glenn\controller\Controller
{
	
	function __construct(\glenn\http\Request $request)
	{
		parent::__construct($request);
		$this->view = new \glenn\view\View('test');
	}

	
	public function index()
	{
		$test = new ExampleTest();
		$this->view()->set('tests', $test->countTestCases());
		$result = $test->run();
		$this->view()->set('results', $result);
	}
	
}
