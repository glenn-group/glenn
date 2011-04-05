<?php

namespace glenn\security;

/**
 * Class containing static methods for validating data.
 */
class Validation
{

	/**
	 * Return true if value is not null or an empty string.
	 *
	 * @param mixed $value
	 * @return boolean 
	 */
	public static function required($value)
	{
		return $value !== null && $value !== '';
	}
	
	/**
	 * Return true if value contains more than the given amount of characters.
	 *
	 * @param mixed $value
	 * @param integer $length
	 * @return boolean 
	 */
	public static function lengthMin($value, $length)
	{
		return \strlen($value) > $length;
	}
	
	/**
	 * Return true if value contains less than the given amount of characters.
	 *
	 * @param mixed $value
	 * @param integer $length
	 * @return boolean 
	 */
	public static function lengthMax($value, $length)
	{
		return \strlen($value) < $length;
	}
	
	/**
	 * Return true if value contains exactly the given amount of characters.
	 *
	 * @param mixed $value
	 * @param integer $length
	 * @return boolean 
	 */
	public static function lengthEqual($value, $length)
	{
		return \strlen($value) === $length;
	}
	
	/**
	 * Return true if value contains only alphanumerical characters.
	 *
	 * @param mixed $value
	 * @return boolean 
	 */
	public static function alphanumeric($value)
	{
		return \ctype_alnum($value);
	}
	
	/**
	 * Return true if value only contains digits (0-9).
	 *
	 * @param mixed $value
	 * @return boolean 
	 */
	public static function digit($value)
	{
		return \ctype_digit($value);
	}
	
	/**
	 * Return true if value contains only alphabetic characters of either case.
	 *
	 * @param mixed $value
	 * @return boolean 
	 */
	public static function alpha($value)
	{
		return \ctype_alpha($value);
	}
	
	/**
	 * Return true if value is a valid e-mail address.
	 *
	 * @param type $value
	 * @return type 
	 */
	public static function email($value)
	{
		return \preg_match('/^[a-z0-9._%-]+@[a-z0-9.-]+\.[a-z]{2,4}$/i', $value) === 1;
	}
	
}