<?php
use glenn\controller\Controller,
    glenn\http\Response,
	glenn\view\View;

class IndexController extends Controller
{
    public function indexAction()
    {
        return new Response(View::load('../app/views/index'));
    }
}