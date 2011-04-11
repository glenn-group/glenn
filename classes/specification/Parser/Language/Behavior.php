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
		$context = $this->requires->interpret($context);
		return $this->ensures->interpret($context);
	}

}