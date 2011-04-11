<?php
namespace glenn\specification\Parser\Language;

class RequiresClause {
	
	private $stmts = array();

	public function __construct($stmts) {
		$this->stmts = $stmts;
	}

	public function interpret($context) {
		echo '<p><strong>Requires</strong></p>';
		foreach($this->stmts as $stmt) {
			$context = $stmt->interpret($context);
		}
		return $context;
	}

}
