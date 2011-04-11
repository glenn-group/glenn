<?php
namespace glenn\specification\Parser\Language;

class Expression {

	private $stmts = array();

	function __construct($stmts) {
		$this->stmts = $stmts;
	}

	public function interpret($context = null) {
		foreach($this->stmts as $stmt) {
			$context = $stmt->interpret($context);
			echo '<hr />';
		}
	}

}
?>
