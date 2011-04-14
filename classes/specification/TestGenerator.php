<?php
namespace glenn\specification;

/**
 * Description of TestGenerator
 *
 * @author peter
 */
class TestGenerator
{

	private $annotation;
	private $nrTests = 0;
	private $tabLevel = 0;
	private $codeBuffer = '';

	public function __construct(Annotations $annotation)
	{
		$this->annotation = $annotation;
	}
	
	public function generateTest($class)
	{
		$annotation = $this->annotation;
		$specs = $annotation->getContracts($class);

		// Create class which shall be filled with methods
		$this->addCode('<?php'."\n", 0);
		$this->addCode('class Test'.$class."\n".'{', 0);
		
		foreach($specs as $name => $spec) {
			$tokenizer = new Parser\Tokenizer($spec);
			$parser = new Parser\Parser($tokenizer);
			$programModel = $parser->parseProgram();
			
			$this->compileToPHP($name, $programModel->interpret());
		}

		$this->addCode('}', 0);
		
		$dir = '../app/tests'.str_replace('\\', '/', substr($class, 0, strrpos($class, '\\')));
		
		if ( ! \file_exists($dir)) {
			mkdir($dir, 0777, true);
		}

		// Write to file
		return $this->flushBuffer('../app/tests'.str_replace('\\','/',$class).'.php');
	}

	private function compileToPHP($name, $code)
	{
		// Create method with test
		$res = '';
		foreach($code as $c) {
			$this->addCode(
				'public function ' . $name . '_' . $c['type'] . '_' . $this->nrTests++ . "\n{",
				1
			);

			$this->addCode(
				'if (' . $c['requires'] . ") { ",
				2
			);

			$this->addCode(
				'$this->assertTrue(' .
				$c['ensures'] . ')',
				3
			);

			$this->addCode(
				'}',
				2
			);

			$this->addCode(
				'}'."\n",
				1
			);
		}

		return $res;
	}

	private function addCode($code, $tabLevel)
				{
		$tabs = \str_repeat("\t", $tabLevel);
		$code = $tabs . \str_replace("\n", "\n".$tabs, $code);
		$this->buffer .= $code . "\n";
	}

	private function flushBuffer($file = null)
	{
		$code = $this->buffer;
		$this->buffer = '';

		if ($file == null) {
			return $code;
			
		} else {
			return \file_put_contents($file, $code);
		}
	}
}
