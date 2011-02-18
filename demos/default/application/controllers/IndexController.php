<?php
use Glenn\Response;

class IndexController
{
    public function indexAction()
    {
        return new Response("Index page");
    }
    
    public function listAction()
    
    {
        return new Response("About page");
    }
}