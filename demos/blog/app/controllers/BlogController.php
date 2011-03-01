<?php
namespace controllers;

use glenn\controller\Controller,
	glenn\http\Response,
	glenn\view\View,
	models\PostMock;

class BlogController extends Controller
{
	public function indexAction()
	{
		return new Response(
			new View('blog/index', array('posts' => PostMock::find()))
		);
	}
	
	public function newAction()
	{
		return new Response('Hello Create Blog Post!');
	}
	
	public function createAction()
	{
		$post = new \models\PostMock();
		$post->title = $this->request->post('title');
		$post->author = $this->request->post('author');
		$post->content = $this->request->post('content');
		$post->save();
		
		$response = new Response(null, 303);
		$response->addHeader('Location', 'http://glenn.blog.local/blog/');
		return $response;
	}
}