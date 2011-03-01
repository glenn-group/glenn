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
        $response = new Response(null, 303);
        $response->addHeader('Location', 'http://glenn.blog.local/blog/');
        return $response;
    }

}