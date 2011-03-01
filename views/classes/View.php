<?php

class View {

	private $variables = array();
	private $viewName;
	
	public function __construct($view){	
		$this->viewName = $view;
	}
	
    public function render($view) {
	
        if (file_exists($view.".php") ) {
			
			//Temporary line because I don't have controller that does this for me at the moment
			$this->set('content','Aint this some stuff');
			
			extract($this->variables);
			
			ob_start();
			include($view.".php");
            $output = ob_get_contents();
            ob_end_clean();
			return $output;

        } else {
            return "File " .$view.".php not found.";
        }
    }
	
	public function __set($name, $value){
		$this->variables[$name] = $value;
	}
	
	/*Temporary imaginary function that does the functionality of the controller*/
	public function set($name, $value){
		$this->variables[$name] = $value;
	}
	
	function __tostring(){
		return $this->render($this->viewName);
	}
	
}

?>