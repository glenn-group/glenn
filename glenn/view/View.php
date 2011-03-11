<?php
namespace glenn\view;

class View
{
	public static function load($view)
	{
		if (file_exists($view . ".php")) {
			ob_start();
			include($view . ".php");
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		} else {
			return "File " . $view . ".php not found.";
		}
	}
}