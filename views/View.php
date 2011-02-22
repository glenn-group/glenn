<?php

class View {

    public static function load($view) {
        if (file_exists("../".$view.".php") ) {
			$view = "../".$view.".php";
            $output = file_get_contents($view);   
            return $output;
        } else {
            return "File " .$view.".php not found.";
        }
    }
	
	}
}