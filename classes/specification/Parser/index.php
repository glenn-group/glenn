<?php
namespace glenn\specification\Parser;

/**
 * Description of Main
 *
 * @author peter
 */
class Index
{
    public static function main()
	{
		$tokenizer = new Tokenizer(
'
	normal_behavior:
	requires {
		$id != null
	}
	ensures {
		view == "blog/view"
		view_has("blogpost")
		external_methods_called("blogModel:getById", "blogModel:getByDate")
	}

	exceptional_behavior:
	requires {
		$aid == null
	}
	ensures {
		$aid == null
	}

	exceptional_behavior:
	requires {
		$aid == null
	}
	ensures {
		throws("lol")
	}
');
		$parser = new Parser($tokenizer);
		
		$programModel = $parser->parseProgram();

		print_r($programModel->interpret());
	}
}

// Do stuff to initilize program run
spl_autoload_register(function($class) {
	include \str_replace('\\', '/', substr($class, 1+strpos($class, '\\')).'.php');
});

// Start program
Index::main();