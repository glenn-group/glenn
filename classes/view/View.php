<?php
namespace glenn\view;

class View
{
	private $template;

	private $variables = array();
	
	public function __construct($template, array $variables = array())
	{
		$this->template  = $template;
		$this->variables = $variables;
	}

	public function render()
	{
		$file = APP_PATH . 'views/' . $this->template . ".php";
		if (file_exists($file)) {
			extract($this->variables);
			ob_start();
			include $file;
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		} else {
			throw new \InvalidArgumentException("View file '$file' could not be located.");
		}
	}

	public function set($name, $value)
	{
		$this->variables[$name] = $value;
	}
	
	public static function factory($file, $params)
	{
		return new View($file, $params);
	}
	
	public function __set($name, $value)
	{
		$this->set($name, $value);
	}

	public function __tostring()
	{
		return $this->render();
	}
}
