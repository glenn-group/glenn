<?php
namespace app\controller;

use app\model\Post,
	glenn\controller\Controller,
    glenn\http\Response;

class BlogController extends Controller
{
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