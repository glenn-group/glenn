<?php

namespace glenn\http\interfaces;

interface Response
{
    /**
	 * Send response headers and body to the client.
     * 
     */
    public function send();
}

?>
