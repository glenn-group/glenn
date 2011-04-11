<?php
namespace glenn\specification\Parser\Language;

class CallExpression {

	private $method;
	private $arguments;

	public function __construct($method, $arguments) {
		$this->method = $method;
		$this->arguments = $arguments;
	}

	public function interpret($context) {
		echo '<p>call "' . $this->method . '" with args: "';
		foreach($this->arguments as $arg ) {
			echo $arg . ', ';
		}
		echo '"<br />';
	}

}