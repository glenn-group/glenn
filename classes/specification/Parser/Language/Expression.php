<?php
namespace glenn\specification\Parser\Language;

class Expression {

	private $stmts = array();

	function __construct($stmts) {
		$this->stmts = $stmts;
	}

	public function interpret($context = null) {
		$code = array();
		
		foreach($this->stmts as $stmt) {
			$code[] = $stmt->interpret($context);
		}

		return $code;
	}

}
?>
