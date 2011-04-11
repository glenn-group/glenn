<?php
namespace glenn\specification\Parser\Language;

class ComparisonExpression {

	private $left;
	private $right;
	private $operator;

	public function __construct($left, $right, $operator) {
		$this->left = $left;
		$this->right = $right;
		$this->operator = $operator;
	}

	public function interpret($context) {
		echo '<p>check "' . $this->left . ' ' . $this->operator . ' ' . $this->right . '"';
	}

}