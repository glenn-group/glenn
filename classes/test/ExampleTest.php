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
		$this->multiAssert(
			function($value) {
				return array(
					'status' => Validation::email($value) === false,
					'message' => '{value} must not be a valid e-mail address.'
				);
			}, array(
				'santa@ymail,com',
				'santa.clause@hotmail.c',
				'santa¢lause@gmail.com'
			)
		);
	}
	
	public function testSha1()
	{
		$hash = new Hash('password', '1xS19!a', Hash::SHA1);
		$this->assertEqual($hash->hash(), sha1('1xS19!apassword'));
		$this->assertNotEqual($hash->hash(), sha1('1xS19!apassword2'));
	}
	
	public function testExternalDispatch()
	{
		$dispatcher = new \glenn\controller\Dispatcher(new \glenn\router\RouterTree());
		$response = $dispatcher->dispatch(new \glenn\http\Request('http://www.google.com/'));
		$status = (int)$response->status;
		$this->assertEqual($status, 302);
	}
	
}