<?php
namespace glenn\security;

/**
 * The Hash class is used to create hashed strings using the crypt function in PHP.
 * This means hashes generated with this class can transparently be upgraded to a
 * stronger implementation later.
 */
class Hash
{
	/**
	 * Constant for the Blowfish algorithm.
	 */
	const BLOWFISH = 0;
	
	/**
	 * Constant for the SHA-256 algorithm.
	 */
	const SHA256 = 1;
	
	/**
	 * Constant for the SHA-512 algorithm.
	 */
	const SHA512 = 2;
	
	/**
	 * Constant for the SHA-1 algorithm.
	 */
	const SHA1 = 3;
	
	/**
	 * Constant for the MD5 algorithm.
	 */
	const MD5 = 4;

	/* Instance variables */
	private $algorithm;
	private $string;
	private $hash;
	private $salt;

	/**
	 *
	 * @param type $string
	 * @param type $salt 
	 */
	public function __construct($string, $salt, $algorithm = null)
	{
		$this->string = $string;
		$this->salt = $salt;
		$this->algorithm = $algorithm;
	}
	
	/**
	 * Return the salt used for the hashed string.
	 * 
	 * @return string
	 */
	public function salt()
	{
		return $this->salt;
	}
	
	/**
	 * Executes the hash function and returns the value.
	 *
	 * @return string hashed string
	 */
	public function hash()
	{
		if ($this->hash === null) {
			switch ($this->algorithm) {
				case Hash::BLOWFISH:
				case Hash::SHA256:
				case Hash::SHA512:
					$this->hash = \crypt($this->string, $this->salt);
					$this->hash = \substr($this->hash, strlen($this->salt));
					break;
				case Hash::SHA1:
					$this->hash = \sha1($this->salt . $this->string);
					break;
				case Hash::MD5:
					$this->hash = \md5($this->salt . $this->string);
					break;
			}	
		}

		return $this->hash;
	}
	
	/**
	 * Create a new Hash object with a randomly generated salt based on the specified
	 * algorithm and cost paramter.
	 *
	 * @param type $algorithm
	 * @param type $string
	 * @param type $cost
	 * @return Hash 
	 */
	public static function factory($algorithm, $string, $cost = null)
	{
		return new Hash($string, static::generateSalt($algorithm, $cost), $algorithm);
	}
	
	/**
	 * Convenience method for using the factory with the Blowfish crypt() algorithm.
	 *
	 * @param type $string
	 * @param type $cost
	 * @return Hash 
	 */
	public static function blowfish($string, $cost = null)
	{
		return static::factory(Hash::BLOWFISH, $string, $cost);
	}
	
	/**
	 * Convenience method for using the factory with the SHA-256 crypt() algorithm.
	 *
	 * @param type $string
	 * @param type $cost
	 * @return Hash 
	 */
	public static function cryptSha256($string, $cost = null)
	{
		return static::factory(Hash::SHA256, $string, $cost);
	}
	
	/**
	 * Convenience method for using the factory with the SHA-512 crypt() algorithm.
	 *
	 * @param type $string
	 * @param type $cost
	 * @return Hash 
	 */
	public static function cryptSha512($string, $cost = null)
	{
		return static::factory(Hash::SHA512, $string, $cost);
	}
	
	/**
	 * Convenience method for using the factory with the SHA-1 algorithm.
	 *
	 * @param type $string
	 * @return Hash 
	 */
	public static function sha1($string)
	{
		return static::factory(Hash::SHA1, $string);
	}
	
	/**
	 * Convenience method for using the factory with the MD5 algorithm.
	 *
	 * @param type $string
	 * @return Hash 
	 */
	public static function md5($string)
	{
		return static::factory(Hash::MD5, $string);
	}

	/**
	 * Generates a random salt based on the selected algorithm and cost, and then
	 * returns the resulting string.
	 *
	 * @return string random salt
	 */
	public static function generateSalt($algorithm, $cost = null)
	{
		switch ($algorithm) {
			case Hash::BLOWFISH:
				return ($cost === null) ? static::saltBlowfish() : static::saltBlowfish($cost);
				break;
			case Hash::SHA256:
				return ($cost === null) ? static::saltSha256() : static::saltSha256($cost);
				break;
			case Hash::SHA512:
				return ($cost === null) ? static::saltSha512() : static::saltSha512($cost);
				break;
			default:
				return static::randomString(16);
		}
	}

	/**
	 *
	 * @param integer $cost
	 * @return string 
	 */
	private static function saltBlowfish($cost = 4)
	{
		if ($cost < 4 || $cost > 31) {
			throw new OutOfRangeException("Cost must be an integer equal or between 4 and 31.");
		}
		if ($cost < 9) {
			$cost = '0' . $cost;
		}
		$salt = '$2a$' . $cost . '$' . static::randomString(22);
		return $salt;
	}

	/**
	 *
	 * @param integer $cost
	 * @return string 
	 */
	private static function saltSha256($cost = 5000)
	{
		if ($cost < 1000 || $cost > 999999999) {
			throw new OutOfRangeException("Cost must be an integer equal or between 1000 and 999,999,999.");
		}
		$salt = '$5$rounds=' . $cost . '$' . static::randomString(16);
		return $salt;
	}

	/**
	 *
	 * @param integer $cost
	 * @return string 
	 */
	private static function saltSha512($cost = 5000)
	{
		if ($cost < 1000 || $cost > 999999999) {
			throw new OutOfRangeException("Cost must be an integer equal or between 1000 and 999,999,999.");
		}
		$salt = '$6$rounds=' . $cost . '$' . static::randomString(16);
		return $salt;
	}

	/**
	 * Generates a random string containing characters [a-zA-Z0-9./].
	 *
	 * @param integer $length
	 * @return string 
	 */
	private static function randomString($length)
	{
		$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ./0123456789';
		$string = '';
		while (strlen($string) < $length) {
			$string .= $characters[mt_rand(0, strlen($characters) - 1)];
		}
		return $string;
	}

	/**
	 * Return the hash.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->hash();
	}
	
}