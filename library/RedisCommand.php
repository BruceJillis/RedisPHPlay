<?php
	/**
	 * Base class for all commands implemented by PHP Redis Fast
	 * 
	 * @abstract
	 * @package Redis\Commands
	 */
	abstract class RedisCommand {
		protected $name;
		public $persistent = false;

		function __construct() {
			$this->name = get_class($this);
		}

		/**
		 * Validate arguments for a command
		 *
		 * @throws InvalidCommandException if the arguments supplied to the command were incorrect
		 */
		abstract function validate(&$arguments);

		/**
		 * Build a request ready to be sent to the server
		 *
		 * @returns string the serialized command ready to be sent over a socket to the server
		 */
		function build($arguments) {
			return self::serialize(array_merge(array($this->name), $arguments));
		}
	
		protected function serialize($commands) {
			$result = '*' . count($commands) . CRLF;
			foreach($commands as $command)
				$result .= '$' . strlen($command) . CRLF . $command . CRLF;
			return $result;
		}

		/**
		 * Validate if a count ($actual) is larger then $expected.
		 *
		 * @throws SmallerThenException if the actual amount is larger then the expected amount
		 */
		final function validateLargerThen($actual, $expected) {
			if( $actual <= $expected )
				throw new SmallerThenException($this->name, $actual, $expected);
		}

		/**
		 * Validate if a count ($actual) is larger then or equal to $expected.
		 *
		 * @throws SmallerThenException if the actual amount is larger then the expected amount
		 */
		final function validateLargerThenEqual($actual, $expected) {
			if( $actual < $expected )
				throw new SmallerThenException($this->name, $actual, $expected);
		}

		/**
		 * Validate if a count ($actual) is equal to $expected.
		 *
		 * @throws NotEqualToException if the actual amount is larger then the expected amount
		 */
		final function validateEquals($actual, $expected) {
			if( $actual != $expected )
				throw new NotEqualToException($this->name, $actual, $expected);
		}

		/**
		 * Validate if a key contains invalid characters
		 *
		 * @throws InvalidKeyException if the key contains valid characters
		 */
		final function validateKey($key) {
			$matches = array();
			preg_match('/[\w|\d|:]+/i', $key, $matches);
			if( $matches[0] != $key )
				throw new InvalidKeyException($this->name, $key);
		}

		/**
		 * Validate if a value is an int
		 *
		 * @throws NotAnIntException if the value is not an int
		 */
		final function validateInt($value) {
			if( !is_int($value) )
				throw new NotAnIntException($this->name, $value);
		}

		/**
		 * Validate if a value is a string
		 *
		 * @throws NotAnIntException if the value is not a string
		 */
		final function validateString($value) {
			if( !is_string($value) )
				throw new NotAnStringException($this->name, $value);
		}

		/**
		 * Validate if a value is an timestamp
		 *
		 * @throws NotATimestampException if the value is not a valid timestamp
		 */
		final function validateTimestamp($value) {
			if( !is_int($value) )
				throw new NotATimestampException($this->name, $value);
		}

		/**
		 * Validate if a value is an glob-style pattern
		 *
		 * @throws NotATimestampException if the value is not a valid timestamp
		 */
		final function validateGlobStylePattern($value) {
			// todo make reg exp
			if( !is_string($value) )
				throw new NotAGlobStylePatternException($this->name, $value);
		}

		/**
		 * Validate if a value is an glob-style pattern
		 *
		 * @throws NotATimestampException if the value is not a valid timestamp
		 */
		final function validateEnumerate($value, $values) {
			// todo make reg exp
			if( !in_array($value, $values) )
				throw new NotInEnumerationException($this->name, $value, $values);
		}

		/**
		 * Validate if a value is an int or a float.
		 *
		 * @throws NotATimestampException if the value is not a valid timestamp
		 */
		final function validateNumber($value) {
			if( !(is_float($value) || is_int($value)) )
				throw new InvalidArgumentException("not an int, or a float");
		}
	}