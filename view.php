<?php
class View{

	function __construct($view) {
        if (file_exists("views/".$view.".php") ) {
            $output = file_get_contents("views/" . $view . ".php");
            return $output;
        } else {
            return "File " . "views/".$view.".phtml not found.";
        }
    }
	
}

?>