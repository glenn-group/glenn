<?php
namespace glenn\http;

class Response extends Message
{
    /**
     * @var string
     */
    protected $body;
    /**
     * @var int
     */
    protected $status;
    /**
     * @var array
     */
    protected $statuses = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out'
    );

	/**
	 * Construct a new response object from a raw HTTP response string.
	 * 
     * @param  string   $string
     * @return Response
     */
	public static function fromString($string)
	{
		$data = \explode("\r\n\r\n", $string);
		foreach (\explode("\r\n", $data[0]) as $header) {
			if (\strpos($header, 'HTTP') === 0) {
				$status = \substr($header, \strpos($header, ' ') + 1, 3);
			} else {
				$headers[] = $header;
			}
		}
		$response = new self($data[1], $status);
		foreach($headers as $header) {
			$response->addHeader(
				\substr($header, 0, \strpos($header, ':')), 
				\substr($header, \strpos($header, ':') + 1)
			);
		}
		return $response;
	}
	
    /**
     * @param  string   $body
     * @return Response
     */
    public static function internalError($body = null)
    {
        return new self($body, 500);
    }
	
	/**
     * @param  string   $body
     * @return Response
     */
    public static function notFound($body = null)
    {
        return new self($body, 404);
    }
    
    /**
	 * @param  string   $uri
     * @return Response
     */
    public static function redirect($url)
    {
        return new self(null, 302, array('Location' => $url));
    }
    
    /**
     * @param string $body
     * @param int    $status 
     */
    public function __construct($body = null, $status = 200, array $headers = array()) 
    {
        if ($body !== null) {
            $this->body = $body;
        }
        if (array_key_exists($status, $this->statuses)) {
            $this->status = $status;
        }
        $this->headers = $headers;
    }

    /**
     * 
     */
    public function send() 
	{
        header(\sprintf(
            "%s %s %s", 
            $this->protocol, 
            $this->status, 
            $this->statuses[$this->status]
        ));
        foreach ($this->headers as $key => $value) {
            header(\sprintf("%s: %s", $key, $value));
        }
        echo $this->body;
    }
	
	/**
	 * @return string
	 */
	public function body()
	{
		return $this->body;
	}

    /**
     * @return string
     */
    public function __toString() 
	{
        $response = \sprintf(
            "%s %s %s\r\n", 
            $this->protocol, 
            $this->status, 
            $this->statuses[$this->status]
        );
		foreach ($this->headers as $key => $value) {
			$response .= \sprintf("%s: %s\r\n", $key, $value);
		}
		return $response .= "\r\n" . $this->body;
    }
}