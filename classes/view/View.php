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
	public static function factory($template, array $params)
	{
		return new View($template, $params);
	}
	
	/**
	 * @param string $template
	 * @param array  $variables 
	 */
	public function __construct($template = null, array $variables = array())
	{
		if ($template !== null) {
			$this->setTemplate($template);
		}
		$this->variables = $variables;
	}

	/**
	 * Render the view using the set variables.
	 *
	 * @return string
	 */
	public function render()
	{
		if ($this->template === null) {
			throw new \Exception('No template defined');
		}
		extract($this->variables);
		ob_start();
		include $this->template;
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
		$this->template = APP_PATH . 'views/' . $template . '.php';
		if (!file_exists($this->template)) {
			throw new \Exception("View file '$this->template' could not be located.");
		}
		$this->template = $template;
	}
	
	/**
	 * Magic setter fort view variables.
	 *
	 * @param string $name
	 * @param mixed  $value 
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