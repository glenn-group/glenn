<?php
namespace glenn\specification\Parser;

class Token {

	private $type;
	private $value;

	const DIGIT = 1;
	const WORD = 2;
	const STRING = 3;
	const SYMBOL = 4;
	const COMPARE_OPERATOR = 5;

	function __construct($type, $value) {
		$this->type = $type;
		$this->value = $value;
	}

	public function getType() {
		return $this->type;
	}

	public function getValue() {
		return $this->value;
	}

}
?>
