<?php
namespace glenn\specification;

/**
 * Description of Annotations
 *
 * @author peter
 */
class Annotations {

	public function getContracts($class) {
		$res = array();
		$reflection = new \ReflectionClass($class);
		$methods = $reflection->getMethods();

		foreach ($methods as $method) {
			$spec = $method->getDocComment();
			$spec_start = \strpos($spec, '@spec_start');

			if($spec_start !== false) {
				$spec = \substr($spec, $spec_start + 11);
				$spec = \substr($spec, 0, \strpos($spec, '*/'));

				$tokenizer = new Parser\Tokenizer($spec);
				$parser = new Parser\Parser($tokenizer);
				$programModel = $parser->parseProgram();

				echo '<h2>Test of ' . $class . '</h2>';
				print_r($programModel->interpret());
			}
		}

		return $res;
	}

	private function extractValueFromAnnotation($annotation) {
		$parts = \explode('&&', $annotation);
		$res = array();

		$annotation = '';
		foreach($parts as $part) {
			$data = explode('\\', $part);

			// Set annotation, only first part has it specified.
			if($annotation == '') {
				$annotation = rtrim(ltrim($data[0], '@'));
			}

			$data = \explode('\\', $part);
			$res[] = array('annotation' => $annotation, 'value' => rtrim(ltrim($data[1]), ' ;'));
		}
		
		return $res;
	}

}
