<?php
namespace glenn\specification\Parser\Language;

class EnsuresClause {

	private $stmts = array();

	public function __construct($stmts) {
		$this->stmts = $stmts;
	}

	public function interpret($context) {
		echo '<p><strong>Ensures</strong></p>';
		foreach($this->stmts as $stmt) {
			$context = $stmt->interpret($context);
		}
		return $context;
	}

}
