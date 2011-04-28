<?php

namespace glenn\security;

/**
 * Description of Auth
 *
 * @author erikbrannstrom
 */
class Auth implements IAuth
{
	public function login($user, $password)
	{
		$dbUser = \app\model\User::find('first', array('username' => $user));
		if($dbUser->allow_attempt_at > time()) {
			// Must wait longer for next attempt
			return false;
		}
		$password = new Hash($password, substr($dbUser->password, 0, 29), Hash::BLOWFISH);
		if($dbUser->password === $password->hash()) {
			$dbUser->attempts = 0;
			$dbUser->save();
			// TODO: Mark session as authenticated
			return true;
		} else {
			// Force wait after failed login
			$dbUser->attempts++; 
			if($dbUser->attempts % 10 === 0) {
				$dbUser->allow_attempt_at = time() + 60;
			} else {
				$dbUser->allow_attempt_at = time() + 1;
			}
			$dbUser->save();
			return false;
		}
	}

	public function logout()
	{
		// TODO: Mark session as non-authenticated
	}

	public function register(array $data)
	{
		$user = new \app\model\User($data);
		$user->save();
	}

}