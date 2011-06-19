<?php

	/**
	 * Base class for all exceptions having to do with validation errors
	 * 
	 * @package Redis\Exceptions\Validation
	 */
	class ValidationException extends RedisException {
	}
	
	/**
	 * Thrown if an amount is smaller then expected
	 * 
	 * @package Redis\Exceptions\Validation
	 */
	class SmallerThenException extends ValidationException {
		function __construct($name, $actual, $expected) {
			parent::__construct("$name expected '$actual' to be larger then '$expected'", 2000);
		}
	}

	/**
	 * Thrown if an invalid key is encounterd
	 * 
	 * @package Redis\Exceptions\Validation
	 */
	class InvalidKeyException extends ValidationException {
		function __construct($name, $key) {
			parent::__construct("'{$key}' is not a valid key ($name)", 2001);
		}
	}

	/**
	 * Thrown if an amount it expected to be equal but is not equal
	 * 
	 * @package Redis\Exceptions\Validation
	 */
	class NotEqualToException extends ValidationException {
		function __construct($name, $actual, $expected) {
			parent::__construct("'{$name}' $actual is not equal to $expected.", 2002);
		}
	}

	/**
	 * Thrown if a value is expected to be an integer but is not
	 * 
	 * @package Redis\Exceptions\Validation
	 */
	class NotAnIntException extends ValidationException {
		function __construct($name, $value) {
			parent::__construct("'{$name}' $value is not an int.", 2003);
		}
	}

	/**
	 * Thrown if a value is expected to be a string but is not
	 * 
	 * @package Redis\Exceptions\Validation
	 */
	class NotAnStringException extends ValidationException {
		function __construct($name, $value) {
			parent::__construct("'{$name}' $value is not an string.", 2004);
		}
	}

	/**
	 * Thrown if a value is expected to be a timestamp but is not.
	 * 
	 * @package Redis\Exceptions\Validation
	 */
	class NotATimestampException extends ValidationException {
		function __construct($name, $value) {
			parent::__construct("'{$name}' $value is not an timestamp.", 2005);
		}
	}

	/**
	 * Thrown if a value is expected to be a timestamp but is not.
	 * 
	 * @package Redis\Exceptions\Validation
	 */
	class NotAGlobStylePatternException extends ValidationException {
		function __construct($name, $value) {
			parent::__construct("'{$name}' $value is not an glob style pattern.", 2006);
		}
	}

	/**
	 * Thrown if a value is expected from a list of fixed values but is different
	 * 
	 * @package Redis\Exceptions\Validation
	 */
	class NotInEnumerationException extends ValidationException {
		function __construct($name, $value, $values) {
			$values = join(', ', $values);
			parent::__construct("'{$name}' $value is not a member of $values.", 2007);
		}
	}