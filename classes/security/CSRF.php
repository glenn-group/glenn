<?php

namespace glenn\security;

/**
 * Description of CSRF
 *
 * @author erikbrannstrom
 */
class CSRF
{

	public function getToken()
	{
		$sessionId = session_id(); // TODO: use custom Session class
		$secretKey = 'f9%83bHD9!0klD2'; // TODO: store in config
		return hash_hmac('sha256', $sessionId, $secretKey);
	}

	public function validate(\glenn\http\Request $request)
	{
		// Don't bother with safe methods
		if (in_array($request->method, array('GET', 'HEAD'))) {
			return true;
		}
		
		// Use Origin header if available
		if ($request->getHeader('Origin') !== null) {
			/* TODO: Validate according to official specification
			  From http://tools.ietf.org/id/draft-abarth-origin-03.html#same-origin :
				  Let /A/ be the first origin being compared, and let /B/ be the second origin being compared.
				  If either /A/ or /B/ is not a scheme/host/port tuple, return an implementation-defined value.
				  If /A/ and /B/ have scheme components that are not identical, return false.
				  If /A/ and /B/ have host components that are not identical, return false.
				  If /A/ and /B/ have port components that are not identical, return false.
				  Return true.
			 */
		}
		
		// Check CSRF token
		return $request->post('csrf_token') === $this->getToken();
	}

}