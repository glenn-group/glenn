<?php
namespace glenn\specification;

/**
 * Description of TestGenerator
 *
 * @author peter
 */
class TestGenerator {

	private $annotation;
	private $nrTests = 0;

	public function __construct(Annotations $annotation) {
		$this->annotation = $annotation;
	}
	
	public function generateTest($class) {
		$annotation = $this->annotation;
		$specs = $annotation->getContracts($class);

		// Create class which shall be filled with methods
		$file = 'class Test'.$class."\n".'{'."\n";
		
		foreach($specs as $name => $spec) {
			$tokenizer = new Parser\Tokenizer($spec);
			$parser = new Parser\Parser($tokenizer);
			$programModel = $parser->parseProgram();
			
			$file .= $this->compileToPHP($name, $programModel->interpret());
		}

		$file .= '}';

		// Write to file
		echo $file;
	}

	private function compileToPHP($name, $code) {
		// Create method with test
		$res = '';
		foreach($code as $c) {
			$res .= 'public function ' . $name . '_' . $c['type'] . '_' . $this->nrTests++ . " {\n" .print_r($c, true) . "}\n";
		}

		return $res;
	}

	private function getClass($value) {
		return substr($value, 0, strpos($value, '('));
	}

}
