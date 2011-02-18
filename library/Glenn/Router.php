<?php
namespace Glenn;

class Router
{
    public function route(Request $request)
    {
        return array(
            'controller' => 'index',
            'action' => 'index'
        );
    }
}