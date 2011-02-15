<?php
namespace Glenn;

class Response
{
    public $status;
    public $body;

    public function __construct($body = null, $status = 200)
    {
        if ($body !== null) {
            $this->body = $body;
        }

        $this->status = $status;
    }

    public function sendHeaders()
    {
        switch ($this->status) {
            case 200: 
                header("HTTP/1.0 200 OK");
                break;
            case 404: 
                header("HTTP/1.0 404 Not Found");
                break;
            case 500:
                header("HTTP/1.0 500 Internal Server Error");
                break;
        }
        
    }
    
    public function sendBody()
    {
        echo $this->body;
    }

	public function send()
	{
        $this->sendHeaders();
		$this->sendBody();
	}
}