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
		echo '<h2>'.$class.'</h2>';
		foreach($specs as $spec) {
			$tokenizer = new Parser\Tokenizer($spec);
			$parser = new Parser\Parser($tokenizer);
			$programModel = $parser->parseProgram();

			echo '<p>';
			print_r($programModel->interpret());
			echo '</p>';
		}
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