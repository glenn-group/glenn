<?php
namespace glenn\specification\Parser\Language;

class Behavior {

	private $type;
	private $requires;
	private $ensures;

	function __construct($type, $requires, $ensures) {
		$this->type = $type;
		$this->requires = $requires;
		$this->ensures = $ensures;
	}

	public function interpret($context) {
		$code['type'] = $this->type;
		$code['requires'] = $this->requires->interpret($context);
		$code['ensures'] = $this->ensures->interpret($context);

		return $code;
	}

}
