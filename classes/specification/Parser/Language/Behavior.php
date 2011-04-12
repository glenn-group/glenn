<?php
namespace glenn\specification\Parser\Language;

class Behavior {

	private $requires;
	private $ensures;

	function __construct($requires, $ensures) {
		$this->requires = $requires;
		$this->ensures = $ensures;
	}

	public function interpret($context) {
		$code['requires'] = $this->requires->interpret($context);
		$code['ensures'] = $this->ensures->interpret($context);

		return $code;
	}

}