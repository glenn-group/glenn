<?php

namespace glenn\test;

use glenn\security\Validation,
	glenn\security\Hash;

class ExampleTest extends Test
{
	
	public function testEmail()
	{
		$this->assertTrue(Validation::email('erik.brannstrom@gmail.com'));
		$this->assertTrue(Validation::email('erik.brannstrom@ymail.com'));
	}
	
	public function testSha1()
	{
		$hash = Hash::sha1('password');
		$this->assertEqual($hash->hash(), sha1($hash->salt().'password'));
	}
	
}