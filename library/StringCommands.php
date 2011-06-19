<?php
	/**
	 * GET key ~ Get the value of a key
	 * 
	 * Get the value of key. If the key does not exist the special value nil is returned. An error is returned if the value stored at key is not a string, because GET only handles string values.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.0.7
	 * @return mixed Bulk reply: the value of key, or nil when key does not exist.
	 * @package Redis\Commands\Strings
	 */
	class GET extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThen(count($arguments), 0);
			foreach($arguments as $argument) {
				$this->validateKey($argument);
			}
		}
	}

	/**
	 * SET key value ~ Set the string value of a key
	 * 
	 * Set key to hold the string value. If key already holds a value, it is overwritten, regardless of its type. 
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.0.7
	 * @return boolean Status code reply: always OK since SET can't fail.
	 * @package Redis\Commands\Strings
	 */
	class SET extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThen(count($arguments), 0);
			foreach($arguments as $argument) {
				$this->validateKey($argument);
			}
		}
	}