<?php
namespace glenn\specification\Parser\Language;

class RequiresClause {
	
	private $stmts = array();

	public function __construct($stmts) {
		$this->stmts = $stmts;
	}

	public function interpret($context) {
		$code = '';
		foreach($this->stmts as $stmt) {
			$code .= $stmt->interpret($context) . ' && ';
		}
		$code = substr($code, 0, strlen($code) - 4);
		return $code;
	}

}
