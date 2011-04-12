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
				$res[] = $spec;
			}
		}

		return $res;
	}

}
