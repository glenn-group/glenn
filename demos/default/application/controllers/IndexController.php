<?php
namespace Application;

use Glenn\Response;

class IndexController
{
    public function indexAction()
    {
        return new Response("Index page");
    }
    
    public function aboutAction()
    {
        return new Response("About page");
    }
}