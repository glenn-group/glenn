<?php

namespace glenn\test;

use glenn\security\Validation,
	glenn\security\Hash;

class ExampleTest extends Test
{
	
	public function testEmail()
	{
		$this->assertTrue(Validation::email('santa@gmail.com'));
		$this->assertTrue(Validation::email('brandon.flowers.killers@super-secret.mail.mobi'));
		$this->assertFalse(Validation::email('brandon.flowers.killers@super-secret.mail.mobilephone'));
		$this->multiAssert('False', array(
			Validation::email('santa@ymail,com'),
			Validation::email('santa.clause@hotmail.c'),
			Validation::email('santa¢lause@gmail.com')
		));
	}
	
	public function testSha1()
	{
		$hash = new Hash('password', '1xS19!a', Hash::SHA1);
		$this->assertEqual($hash->hash(), sha1('1xS19!apassword'));
		$this->assertNotEqual($hash->hash(), sha1('1xS19!apassword2'));
	}
	
}