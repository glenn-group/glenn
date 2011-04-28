<?php

namespace glenn\security;

/**
 *
 * @author erikbrannstrom
 */
interface IAuth
{
	public function login($user, $password);
	public function logout();
	public function register(array $data);
}