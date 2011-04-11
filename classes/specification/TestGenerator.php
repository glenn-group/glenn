<?php
namespace glenn\specification;

/**
 * Description of TestGenerator
 *
 * @author peter
 */
class TestGenerator {

	private $annotation;

	public function __construct(Annotations $annotation) {
		$this->annotation = $annotation;
	}
	
	public function generateTest($class) {
		$annotation = $this->annotation;
		$specs = $annotation->getContracts($class);
	}

	private function compileAnnotation($value) {
		foreach($value as $val) {
			// Split into several
			$class = '\\Test\\'.\ucfirst($this->getClass($val)) . 'Test';
		}
	}

	private function getClass($value) {
		return substr($value, 0, strpos($value, '('));
	}

}