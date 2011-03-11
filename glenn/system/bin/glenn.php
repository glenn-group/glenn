#!/usr/bin/php
<?php
class Glenn
{
	public static function main(array $argv)
	{
		switch ($argv[1]) {
			case 'help':
				echo "help \n";
				break;
			case 'version':
				echo "1.0 \n";
				break;
		}
	}
}

Glenn::main($_SERVER['argv']);