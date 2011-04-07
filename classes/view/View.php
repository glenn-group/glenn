<?php
namespace glenn\view;

class View
{
	private $template;
	private $file;
	private $variables = array();
	
	public function __construct($template, array $variables = array())
	{
		$this->setTemplate($template);
		$this->variables = $variables;
	}

	/**
	 * Render the view using the set variables.
	 *
	 * @return string
	 */
	public function render()
	{
		extract($this->variables);
		ob_start();
		include $this->file;
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/**
	 * Set the a variable to be used in the view.
	 *
	 * @param string $name
	 * @param mixed $value 
	 */
	public function set($name, $value)
	{
		$this->variables[$name] = $value;
	}
	
	/**
	 * Set the template file.
	 *
	 * @param string $template 
	 */
	public function setTemplate($template)
	{
		$file = \glenn\loader\Loader::find('views', $template.'.phtml');
		if (!file_exists($file)) {
			throw new \InvalidArgumentException("View file '$file' could not be located.");
		}
		$this->file = $file;
		$this->template  = $template;
	}
	
	/**
	 * Factory for creating view instances.
	 *
	 * @param string $file
	 * @param array $params
	 * @return View 
	 */
	public static function factory($file, array $params = array())
	{
		return new View($file, $params);
	}
	
	/**
	 * Magic setter fort view variables.
	 *
	 * @param string $name
	 * @param mixed $value 
	 */
	public function __set($name, $value)
	{
		$this->set($name, $value);
	}

	/**
	 * Render the view.
	 *
	 * @return string
	 */
	public function __tostring()
	{
		return $this->render();
	}
}
