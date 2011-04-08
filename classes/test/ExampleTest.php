<?php

namespace glenn\test;

use glenn\security\Validation,
	glenn\security\Hash;

class ExampleTest extends Test
{
	
	public function testEmail()
	{
		$this->assertTrue(Validation::email('santa@gmail.com'));
		$this->assertFalse(Validation::email('santa@ymail,com'));
	}
	
	public function testSha1()
	{
		$hash = new Hash('password', '1xS19!a', Hash::SHA1);
		$this->assertEqual($hash->hash(), sha1('1xS19!apassword'));
	}
	
}