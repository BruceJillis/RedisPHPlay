<?php
	/**
	 * DEL key [key ...] ~ Delete a key
	 * 
	 * Removes the specified keys. A key is ignored if it does not exist.
	 * 
	 * Time complexity: O(N) where N is the number of keys that will be removed. When a key to remove holds a value other than a string, the individual complexity for this key is O(M) where M is the number of 
	 * elements in the list, set, sorted set or hash. Removing a single key that holds a string value is O(1). 
	 * 
	 * @since: 0.0.7
	 * @return int Integer reply: The number of keys that were removed.
	 * @package Redis\Commands\Keys
	 */
	class DEL extends RedisCommand {
		function validate(&$arguments) {
			RedisCommand::validateLargerThen(count($arguments), 0);
			foreach($arguments as $argument) {
				RedisCommand::validateKey($argument);
			}
		}
	}

	/**
	 * EXISTS key ~ Determine if a key exists
	 * 
	 * Returns if key exists.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.0.7
	 * @return int Integer reply: specifically: 1 if key exists, 0 if the key does not exist
	 * @package Redis\Commands\Keys
	 */
	class EXISTS extends RedisCommand {
		function validate(&$arguments) {
			RedisCommand::validateEquals(count($arguments), 1);
			RedisCommand::validateKey($arguments[0]);
		}

		function output($line) {
			return intval($line) == 1;
		}
	}

	/**
	 * EXPIRE key seconds ~ Set a key's time to live in seconds
	 * 
	 * Set a timeout on key. After the timeout has expired, the key will automatically be deleted. A key with an associated timeout is said to be volatile in Redis terminology.
	 * For Redis versions < 2.1.3, existing timeouts cannot be overwritten. So, if key already has an associated timeout, it will do nothing and return 0. Since Redis 2.1.3, you can update the timeout of a key. 
	 * It is also possible to remove the timeout using the PERSIST command. See the page on key expiry for more information.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.0.9
	 * @return int Integer reply: specifically: 1 if the timeout was set, 0 if the key does not exist or the timeout could not be set
	 * @package Redis\Commands\Keys
	 */
	class EXPIRE extends RedisCommand {
		function validate(&$arguments) {
			RedisCommand::validateEquals(count($arguments), 2);
			RedisCommand::validateKey($arguments[0]);
			RedisCommand::validateInt($arguments[1]);
		}

		function output($line) {
			return intval($line) == 1;
		}
	}

	/**
	 * EXPIREAT key timestamp ~ Set the expiration for a key as a UNIX timestamp
	 * 
	 * Set a timeout on key. After the timeout has expired, the key will automatically be deleted. A key with an associated timeout is said to be volatile in Redis terminology.
	 * EXPIREAT has the same effect and semantic as EXPIRE, but instead of specifying the number of seconds representing the TTL (time to live), it takes an absolute UNIX timestamp (seconds since January 1, 1970).
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 1.1.0
	 * @return int Integer reply: specifically: 1 if the timeout was set, 0 if the key does not exist or the timeout could not be set
	 * @package Redis\Commands\Keys
	 */
	class EXPIREAT extends RedisCommand {
		function validate(&$arguments) {
			RedisCommand::validateEquals(count($arguments), 2);
			RedisCommand::validateKey($arguments[0]);
			RedisCommand::validateTimestamp($arguments[1]);
		}

		function output($line) {
			return intval($line) == 1;
		}
	}

	/**
	 * KEYS pattern ~ Find all keys matching the given pattern
	 * 
	 * Returns all keys matching pattern. While the time complexity for this operation is O(N), the constant times are fairly low. For example, Redis running on an entry level laptop can scan a 1 million key 
	 * database in 40 milliseconds.Warning: consider KEYS as a command that should only be used in production environments with extreme care. It may ruin performance when it is executed against large databases. 
	 * This command is intended for debugging and special operations, such as changing your keyspace layout. Don't use KEYS in your regular application code. If you're looking for a way to find keys in a subset 
	 * of your keyspace, consider using sets.
	 * 
	 * Supported glob-style patterns:
	 *   h?llo matches hello, hallo and hxllo
	 *   h*llo matches hllo and heeeello
	 *   h[ae]llo matches hello and hallo, but not hillo
	 * 
	 * Use \ to escape special characters if you want to match them verbatim.
	 * 
	 * Time complexity: O(N) with N being the number of keys in the database, under the assumption that the key names in the database and the given pattern have limited length.
	 * 
	 * @since: 0.0.7
	 * @return array Multi-bulk reply: list of keys matching pattern.
	 * @package Redis\Commands\Keys
	 */
	class KEYS extends RedisCommand {
		function validate(&$arguments) {
			RedisCommand::validateEquals(count($arguments), 1);
			RedisCommand::validateGlobStylePattern($arguments[0]);
		}
	}

	/**
	 * MOVE key db ~ Move a key to another database
	 * 
	 * Move key from the currently selected database (see SELECT) to the specified destination database. When key already exists in the destination database, or it does not exist in the source database, it does nothing. 
	 * It is possible to use MOVE as a locking primitive because of this.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.0.7
	 * @return int Integer reply: specifically: 1 if the key was moved, 0 if the key was not moved
	 * @package Redis\Commands\Keys
	 */
	class MOVE extends RedisCommand {
		function validate(&$arguments) {
			RedisCommand::validateEquals(count($arguments), 2);
			RedisCommand::validateKey($arguments[0]);
			RedisCommand::validateInt($arguments[1]);
		}

		function output($line) {
			return intval($line) == 1;
		}
	}

	/**
	 * PERSIST key ~ Remove the expiration from a key
	 * 
	 * Remove the existing timeout on key.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 2.1.2
	 * @return int Integer reply: specifically: 1 if the timeout was moved, 0 if the key does not exist or does not have an associated timeout
	 * @package Redis\Commands\Keys
	 */
	class PERSIST extends RedisCommand {
		function validate(&$arguments) {
			RedisCommand::validateEquals(count($arguments), 1);
			RedisCommand::validateKey($arguments[0]);
		}

		function output($line) {
			return intval($line) == 1;
		}
	}

	/**
	 * RANDOMKEY ~ Return a random key from the keyspace
	 * 
	 * Return a random key from the currently selected database.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.0.7
	 * @return array Bulk reply: the random key, or nil when the database is empty.
	 * @package Redis\Commands\Keys
	 */
	class RANDOMKEY extends RedisCommand {
		function validate(&$arguments) {
			RedisCommand::validateEquals(count($arguments), 0);
		}

		function output($line) {
			$parts = explode('_', $line);
			return $parts[0];
		}
	}

	/**
	 * RENAME key newkey ~ Rename a key
	 * 
	 * Renames key to newkey. It returns an error when the source and destination names are the same, or when key does not exist. If newkey already exists it is overwritten.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.0.7
	 * @return boolean Status code reply
	 * @package Redis\Commands\Keys
	 */
	class RENAME extends RedisCommand {
		function validate(&$arguments) {
			RedisCommand::validateEquals(count($arguments), 2);
			RedisCommand::validateKey($arguments[0]);
			RedisCommand::validateKey($arguments[1]);
		}
	}

	/**
	 * RENAMENX key newkey ~ Rename a key, only if the new key does not exist
	 * 
	 * Renames key to newkey. It returns an error when the source and destination names are the same, or when key does not exist. If newkey already exists it is overwritten.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.0.7
	 * @return int Integer reply, specifically: 1 if key was renamed to newkey. 0 if newkey already exists.
	 * @package Redis\Commands\Keys
	 */
	class RENAMENX extends RedisCommand {
		function validate(&$arguments) {
			RedisCommand::validateEquals(count($arguments), 2);
			RedisCommand::validateKey($arguments[0]);
			RedisCommand::validateKey($arguments[1]);
		}
	}

	/**
	 * SORT key [BY pattern] [LIMIT offset count] [GET pattern [GET pattern ...]] [ASC|DESC] [ALPHA] [STORE destination] ~ Sort the elements in a list, set or sorted set
	 * 
	 * Renames key to newkey. It returns an error when the source and destination names are the same, or when key does not exist. If newkey already exists it is overwritten.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.0.7
	 * @return int Integer reply, specifically: 1 if key was renamed to newkey. 0 if newkey already exists.
	 * @package Redis\Commands\Keys
	 */
	class SORT extends RedisCommand {
		function validate(&$arguments) {
			RedisCommand::validateLargerThenEqual(count($arguments), 1);
		}
	}

	/**
	 * TTL key ~ Get the time to live for a key
	 * 
	 * Returns the remaining time to live of a key that has a timeout. This introspection capability allows a Redis client to check how many seconds a given key will continue to be part of the dataset.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.0.7
	 * @return int Integer reply: TTL in seconds or -1 when key does not exist or does not have a timeout.
	 * @package Redis\Commands\Keys
	 */
	class TTL extends RedisCommand {
		function validate(&$arguments) {
			RedisCommand::validateEquals(count($arguments), 1);
			RedisCommand::validateKey($arguments[0]);
		}
	}

	/**
	 * TYPE key ~ Determine the type stored at key
	 * 
	 * Returns the string representation of the type of the value stored at key. The different types that can be returned are: string, list, set, zset and hash.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.0.7
	 * @return string Status code reply: type of key, or none when key does not exist.
	 * @package Redis\Commands\Keys
	 */
	class TYPE extends RedisCommand {
		function validate(&$arguments) {
			RedisCommand::validateEquals(count($arguments), 1);
			RedisCommand::validateKey($arguments[0]);
		}

		function output($line) {
			return $line;
		}
	}