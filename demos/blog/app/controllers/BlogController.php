<?php
namespace controllers;

use glenn\controller\Controller,
    glenn\http\Response,
    glenn\view\View,
	models\Post,
	glenn\event\EventHandler,
	glenn\event\Event;

class BlogController extends Controller {
	
	 public function __construct(\glenn\http\Request $request, \glenn\view\View $view)
	 {
		parent::__construct($request, $view);
		EventHandler::bind('app.blog.index.post', function(Event $e) {
			$e->subject()->view()->posts = Post::all();
		});
	 }

	 public function indexAction() 
	 {
		$obj = new \stdClass();
		$obj->title = 'Title';
		$obj->content = 'Content';
		$this->view->posts = array($obj);
		EventHandler::trigger(new Event($this, 'app.blog.index.post'));
    }

    public function newAction() 
	{
        
    }

    public function createAction() 
	{
        return Response::redirect('http://glenn.blog.local/blog/', 303);
    }

}