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
		$code = $this->method . '(';
		$args = '';
		foreach($this->arguments as $arg ) {
			$args .=  $arg . ', ';
		}
		$args = substr($args, 0, strlen($args)-2) . ')';
		$code .= $args;

		return $code;
	}

}