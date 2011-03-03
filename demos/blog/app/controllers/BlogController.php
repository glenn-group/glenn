<?php
namespace controllers;

use glenn\controller\Controller,
    glenn\http\Response,
    glenn\view\View,
	models\Post;

class BlogController extends Controller {

    public function indexAction() 
	{
		$this->view->posts = Post::all();
    }

    public function newAction() 
	{
        
    }

    public function createAction() 
	{
        return Response::redirect('http://glenn.blog.local/blog/', 303);
    }

}