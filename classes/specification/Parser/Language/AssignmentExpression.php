<?php
namespace glenn\specification\Parser\Language;

class AssignmentExpression {

	private $variable;
	private $value;

	public function __construct(\Parser\Parser $parser, \Parser\Tokenizer $tokenizer) {
		$this->variable = new Variable($parser, $tokenizer);
	}

	public function interpret($context) {

	}

}