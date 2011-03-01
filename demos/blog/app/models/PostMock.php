<?php
namespace models;

class PostMock
{
	public $title;
	public $author;
	public $content;
	
	public function __construct($title, $author, $content)
	{
		$this->title = $title;
		$this->author = $author;
		$this->content = $content;
	}

	public function save()
	{
		return true;
	}
	
	public static function find()
	{
		return array(
			new self('php is good for you', 'jens', 'lorem ipsum'),
			new self('php is good for you too', 'erik', 'lorem ipsum'),
			new self('learn you some php for great good', 'jens', 'lorem ipsum')
		);
	}
}