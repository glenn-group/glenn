<?php
namespace glenn\specification\Parser;

class Parser {

	private $tokenizer = null;

	public function __construct(Tokenizer $tokenizer) {
		$this->tokenizer = $tokenizer;
	}

	public function skipToken($tokenValue) {
		if ($this->tokenizer->readNextToken()->getValue() != $tokenValue) {
			throw new \Exception('Not expected token');
		}
	}

	public function parseStatement(Token $token) {
		if ($token->getType() != Token::WORD) {
			throw new \Exception("Syntax error");
		}

		$value = $token->getValue();

		if ($value == 'normal_behavior') {
			return $this->parseNormalBehavior();
		}

		if($value == 'exceptional_behavior') {
			return $this->parseNormalBehavior();
		}
	}

	public function parseProgram()
	{
		$tokenizer = $this->tokenizer;

		$stmts = array();
		while ( ($token = $tokenizer->readNextToken()) != null) {
			$stmts[] = $this->parseStatement($token);
		}

		return new Language\Expression($stmts);
	}

	private function parseNormalBehavior()
	{
		$this->skipToken(':');

		$requireClause = $this->parseRequires();
		$ensuresClause = $this->parseEnsures();

		return new Language\Behavior($requireClause, $ensuresClause);
	}

	private function parseExceptionalBehavior()
	{
		$this->skipToken(':');

		$requireClause = $this->parseRequires();
		$ensuresClause = $this->parseEnsures();

		return new Language\Behavior($requireClause, $ensuresClause);
	}

	private function parseEnsures()
	{
		$this->skipToken('ensures');
		$this->skipToken('{');

		$stmts = array();
		$i = 0;
		while ( ($token = $this->tokenizer->readNextToken()->getValue()) != '}' )  {
			$stmts[] = $this->parseComparisonOrCall($token);
			$i++;
		}

		return new Language\EnsuresClause($stmts);
	}

	private function parseRequires()
	{
		$this->skipToken('requires');
		$this->skipToken('{');

		$stmts = array();
		$i = 0;
		while ( ($token = $this->tokenizer->readNextToken()->getValue()) != '}' )  {
			$stmts[] = $this->parseComparisonOrCall($token);
			$i++;
		}

		return new Language\RequiresClause($stmts);
	}

	private function parseComparisonOrCall($left)
	{
		$symbol = $this->tokenizer->readNextToken()->getValue();
		if ($symbol != '(') {
			$right = $this->tokenizer->readNextToken()->getValue();
			return new Language\ComparisonExpression($left, $right, $symbol);
			
		} else {
			$arguments = array();
			while ( ($token = $this->tokenizer->readNextToken()->getValue()) != ')' )  {
				if ($token == ',') {
					$arguments[] = $this->tokenizer->readNextToken()->getValue();
				} else {
					$arguments[] = $token;
				}
			}

			return new Language\CallExpression($left, $arguments);
		}
	}

}
?>
