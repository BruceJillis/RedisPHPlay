<?php
	/**
	 * HDEL key field ~ Delete a hash field
	 *
	 * Removes field from the hash stored at key.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 1.3.10
	 * @return int Integer reply: specifically, 1 if field was present in the hash and is now removed. 0 if field does not exist in the hash, or key does not exist.
	 * @package Redis\Commands\Hashes
	 */
	class HDEL extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 2);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * HEXISTS key field ~ Determine if a hash field exists
	 *
	 * Returns if field is an existing field in the hash stored at key.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 1.3.10
	 * @return int Integer reply, specifically: 1 if the hash contains field. 0 if the hash does not contain field, or key does not exist.
	 * @package Redis\Commands\Hashes
	 */
	class HEXISTS extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 2);
			$this->validateKey($arguments[0]);
		}

		function output($line) {
			return intval($line) == 1;
		}
	}

	/**
	 * HGET key field ~ Get the value of a hash field
	 *
	 * Returns the value associated with field in the hash stored at key.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 1.3.10
	 * @return array Bulk reply: the value associated with field, or nil when field is not present in the hash or key does not exist.
	 * @package Redis\Commands\Hashes
	 */
	class HGET extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 2);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * HGETALL key ~ Get all the fields and values in a hash
	 *
	 * Returns all fields and values of the hash stored at key. In the returned value, every field name is followed by its value, so the length of the reply is twice the size of the hash.
	 * 
	 * Time complexity: O(N) where N is the size of the hash.
	 * 
	 * @since: 1.3.10
	 * @return array Multi-bulk reply: list of fields and their values stored in the hash, or an empty list when key does not exist.
	 * @package Redis\Commands\Hashes
	 */
	class HGETALL extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 1);
			$this->validateKey($arguments[0]);
		}

		function output($array) {
			$array = array_chunk($array, 2);
			$result = array();
			foreach($array as $value) {
				$result[$value[0]] = $value[1];
			}
			return $result;
		}
	}

	/**
	 * HINCRBY key field increment ~ Increment the integer value of a hash field by the given number
	 *
	 * Increments the number stored at field in the hash stored at key by increment. If key does not exist, a new key holding a hash is created. If field does not exist or holds a string that cannot be interpreted as 
	 * integer, the value is set to 0 before the operation is performed. The range of values supported by HINCRBY is limited to 64 bit signed integers. Since the increment argument is signed, both increment and decrement 
	 * operations can be performed.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 1.3.10
	 * @return int Integer reply: the value at field after the increment operation.
	 * @package Redis\Commands\Hashes
	 */
	class HINCRBY extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 3);
			$this->validateKey($arguments[0]);
			$this->validateInt($arguments[2]);
		}
	}

	/**
	 * HKEYS key ~ Get all the fields in a hash
	 *
	 * Returns all field names in the hash stored at key.
	 * 
	 * Time complexity: O(N) where N is the size of the hash.
	 * 
	 * @since: 1.3.10
	 * @return array Multi-bulk reply: list of fields in the hash, or an empty list when key does not exist.
	 * @package Redis\Commands\Hashes
	 */
	class HKEYS extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 1);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * HLEN key ~ Get the number of fields in a hash
	 *
	 * Returns the number of fields contained in the hash stored at key.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 1.3.10
	 * @return int Integer reply: number of fields in the hash, or 0 when key does not exist.
	 * @package Redis\Commands\Hashes
	 */
	class HLEN extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 1);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * HMGET key field [field ...] ~ Get the values of all the given hash fields
	 *
	 * Returns the values associated with the specified fields in the hash stored at key.
	 * For every field that does not exist in the hash, a nil value is returned. Because a non-existing keys are treated as empty hashes, running HMGET against a non-existing key will return a list of nil values.
	 * 
	 * Time complexity: O(N) where N is the number of fields being requested.
	 * 
	 * @since: 1.3.10
	 * @return array Multi-bulk reply: list of values associated with the given fields, in the same order as they are requested.
	 * @package Redis\Commands\Hashes
	 */
	class HMGET extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThen(count($arguments), 2);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * HMSET key field value [field value ...] ~ Set multiple hash fields to multiple values
	 *
	 * Sets the specified fields to their respective values in the hash stored at key. This command overwrites any existing fields in the hash. If key does not exist, a new key holding a hash is created.
	 * 
	 * Time complexity: O(N) where N is the number of fields being set.
	 * 
	 * @since: 1.3.10
	 * @return boolean Status code reply
	 * @package Redis\Commands\Hashes
	 */
	class HMSET extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThen(count($arguments), 2);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * HSET key field value ~ Set the string value of a hash field
	 *
	 * Sets field in the hash stored at key to value. If key does not exist, a new key holding a hash is created. If field already exists in the hash, it is overwritten.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 1.3.10
	 * @return int Integer reply, specifically: 1 if field is a new field in the hash and value was set. 0 if field already exists in the hash and the value was updated.
	 * @package Redis\Commands\Hashes
	 */
	class HSET extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 3);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * HSETNX key field value ~ Set the value of a hash field, only if the field does not exist
	 *
	 * Sets field in the hash stored at key to value, only if field does not yet exist. If key does not exist, a new key holding a hash is created. If field already exists, this operation has no effect.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 1.3.10
	 * @return int Integer reply, specifically: 1 if field is a new field in the hash and value was set. 0 if field already exists in the hash and no operation was performed.
	 * @package Redis\Commands\Hashes
	 */
	class HSETNX extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThenEqual(count($arguments), 3);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * HVALS key ~ Get all the values in a hash
	 *
	 * Returns all values in the hash stored at key.
	 * 
	 * Time complexity: O(N) where N is the size of the hash.
	 * 
	 * @since: 1.3.10
	 * @return array Multi-bulk reply: list of values in the hash, or an empty list when key does not exist.
	 * @package Redis\Commands\Hashes
	 */
	class HVALS extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 1);
			$this->validateKey($arguments[0]);
		}
	}

