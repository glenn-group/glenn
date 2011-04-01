<?php
namespace glenn\view;

class View
{
	/**
	 * @var string
	 */
	protected $template;

	/**
	 * @var array
	 */
	protected $variables = array();
	
	/**
	 * @param  string $template
	 * @param  array  $params
	 * @return View 
	 */
	public static function factory($template = null, array $params = array())
	{
		return new View($template, $params);
	}
	
	/**
	 * @param string $template
	 * @param array  $variables 
	 */
	public function __construct($template = null, array $variables = array())
	{
		$this->template  = $template;
		$this->variables = $variables;
	}

	public function render()
	{
		if ($this->template === null) {
			throw new \Exception('No template defined');
		}
		$file = APP_PATH . 'views/' . $this->template . ".php";
		if (file_exists($file)) {
			extract($this->variables);
			ob_start();
			include $file;
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		} else {
			throw new \Exception("View file '$file' could not be located.");
		}
	}

	public function set($name, $value)
	{
		$this->variables[$name] = $value;
	}
	
	public function setTemplate($template)
	{
		$this->template = $template;
	}
	
	public function __set($name, $value)
	{
		$this->set($name, $value);
	}
}
