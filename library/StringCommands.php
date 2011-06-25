<?php
	/**
	 * APPEND key value ~ Append a value to a key
	 * 
	 * If key already exists and is a string, this command appends the value at the end of the string. If key does not exist it is created and set as an empty string, so APPEND will be similar to SET in this special case.
	 * 
	 * Time complexity: O(1). The amortized time complexity is O(1) assuming the appended value is small and the already present value is of any size, since the dynamic string library used by Redis will double the free 
	 * space available on every reallocation.
	 * 
	 * @since: 0.0.7
	 * @return int Integer reply: the length of the string after the append operation.
	 * @package Redis\Commands\Strings
	 */
	class APPEND extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 2);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * DECR key ~ Decrement the integer value of a key by one
	 * 
	 * Decrements the number stored at key by one. If the key does not exist, it is set to 0 before performing the operation. An error is returned if the key contains a value of the wrong type or contains a string that is 
	 * not representable as integer. This operation is limited to 64 bit signed integers. See INCR for extra information on increment/decrement operations.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.0.7
	 * @return int Integer reply: the value of key after the decrement
	 * @package Redis\Commands\Strings
	 */
	class DECR extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 1);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * DECRBY key decrement ~ Decrement the integer value of a key by the given number
	 * 
	 * Decrements the number stored at key by decrement. If the key does not exist, it is set to 0 before performing the operation. An error is returned if the key contains a value of the wrong type or contains a string that 
	 * is not representable as integer. This operation is limited to 64 bit signed integers.
	 * 
	 * See INCR for extra information on increment/decrement operations.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.0.7
	 * @return int Integer reply: the value of key after the decrement
	 * @package Redis\Commands\Strings
	 */
	class DECRBY extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 2);
			$this->validateKey($arguments[0]);
			$this->validateInt($arguments[1]);
		}
	}

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
	 * GETBIT key offset ~ Returns the bit value at offset in the string value stored at key
	 * 
	 * Returns the bit value at offset in the string value stored at key. When offset is beyond the string length, the string is assumed to be a contiguous space with 0 bits. When key does not exist it is assumed to be an empty 
	 * string, so offset is always out of range and the value is also assumed to be a contiguous space with 0 bits.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 2.1.8
	 * @return int Integer reply: the bit value stored at offset.
	 * @package Redis\Commands\Strings
	class GETBIT extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 2);
			$this->validateKey($arguments[0]);
			$this->validateInt($arguments[1]);
		}
	}
	 */

	/**
	 * GETRANGE key start end ~ Get a substring of the string stored at a key
	 * 
	 * Warning: this command was renamed to GETRANGE, it is called SUBSTR in Redis versions <= 2.0.
	 * Returns the substring of the string value stored at key, determined by the offsets start and end (both are inclusive). Negative offsets can be used in order to provide an offset starting from the end of the 
	 * string. So -1 means the last character, -2 the penultimate and so forth. The function handles out of range requests by limiting the resulting range to the actual length of the string.
	 * 
	 * Time complexity: O(N) where N is the length of the returned string. The complexity is ultimately determined by the returned length, but because creating a substring from an existing string is very cheap, it 
	 * can be considered O(1) for small strings.
	 * 
	 * @since: 2.1.8
	 * @return array Bulk reply
	 * @package Redis\Commands\Strings
	class GETRANGE extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 3);
			$this->validateKey($arguments[0]);
			$this->validateInt($arguments[1]);
			$this->validateInt($arguments[2]);
		}
	}
	 */

	/**
	 * GETSET key value ~ Set the string value of a key and return its old value
	 * 
	 * Atomically sets key to value and returns the old value stored at key. Returns an error when key exists but does not hold a string value.
	 * 
	 * Design pattern
	 * 
	 * GETSET can be used together with INCR for counting with atomic reset. For example: a process may call INCR against the key mycounter every time some event occurs, but from time to time we need to get the value of 
	 * the counter and reset it to zero atomically. This can be done using GETSET mycounter "0":
	 * 
	 *   redis> INCR mycounter
	 * 
	 *   (integer) 1
	 * 
	 *   redis> GETSET mycounter "0"
	 * 
	 *   "1"
	 * 
	 *   redis> GET mycounter
	 * 
	 *   "0"
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.0.91
	 * @return mixed Bulk reply: the old value stored at key, or nil when key did not exist.
	 * @package Redis\Commands\Strings
	 */
	class GETSET extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 2);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * INCR key ~ Increment the integer value of a key by one
	 * 
	 * Increments the number stored at key by one. If the key does not exist, it is set to 0 before performing the operation. An error is returned if the key contains a value of the wrong type or contains a string that is 
	 * not representable as integer. This operation is limited to 64 bit signed integers.Redis stores integers in their integer representation, so for string values that actually hold an integer, there is no overhead for 
	 * storing the string representation of the integer.
	 * 
	 * Note: this is a string operation because Redis does not have a dedicated integer type. The the string stored at the key is interpreted as a base-10 64 bit signed integer to execute the operation.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.0.7
	 * @return int Integer reply: the value of key after the increment
	 * @package Redis\Commands\Strings
	 */
	class INCR extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 1);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * INCRBY key increment ~ Increment the integer value of a key by the given number
	 * 
	 * Increments the number stored at key by increment. If the key does not exist, it is set to 0 before performing the operation. An error is returned if the key contains a value of the wrong type or contains a string that is not 
	 * representable as integer. This operation is limited to 64 bit signed integers.
	 * 
	 * See INCR for extra information on increment/decrement operations.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.0.7
	 * @return int Integer reply: the value of key after the increment
	 * @package Redis\Commands\Strings
	 */
	class INCRBY extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 2);
			$this->validateKey($arguments[0]);
			$this->validateInt($arguments[1]);
		}
	}

	/**
	 * MGET key [key ...] ~ Get the values of all the given keys
	 * 
	 * Returns the values of all specified keys. For every key that does not hold a string value or does not exist, the special value nil is returned. Because of this, the operation never fails.
	 * 
	 * Time complexity: O(N) where N is the number of keys to retrieve.
	 * 
	 * @since: 0.0.7
	 * @return array Multi-bulk reply: list of values at the specified keys.
	 * @package Redis\Commands\Strings
	 */
	class MGET extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThen(count($arguments), 1);
			foreach($arguments as $argument) {
				$this->validateKey($argument);
			}
		}
	}

	/**
	 * MSET key [key ...] ~ Get the values of all the given keys
	 * 
	 * Sets the given keys to their respective values. MSET replaces existing values with new values, just as regular SET. See MSETNX if you don't want to overwrite existing values.
	 * MSET is atomic, so all given keys are set at once. It is not possible for clients to see that some of the keys were updated while others are unchanged.
	 * 
	 * Time complexity: O(N) where N is the number of keys to set.
	 * 
	 * @since: 0.0.7
	 * @return boolean Status code reply: always OK since MSET can't fail.
	 * @package Redis\Commands\Strings
	 */
	class MSET extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThen(count($arguments), 1);
			foreach($arguments as $argument) {
				$this->validateKey($argument);
			}
		}
	}

	/**
	 * MSETNX key value [key value ...] ~ Set multiple keys to multiple values, only if none of the keys exist
	 * 
	 * Sets the given keys to their respective values. MSETNX will not perform any operation at all even if just a single key already exists.
	 * Because of this semantic MSETNX can be used in order to set different keys representing different fields of an unique logic object in a way that ensures that either all the fields or none at all are set.
	 * MSETNX is atomic, so all given keys are set at once. It is not possible for clients to see that some of the keys were updated while others are unchanged.
	 * 
	 * Time complexity: O(N) where N is the number of keys to set.
	 * 
	 * @since: 1.0.01
	 * @return int Integer reply, specifically: 1 if the all the keys were set. 0 if no key was set (at least one key already existed).
	 * @package Redis\Commands\Strings
	 */
	class MSETNX extends RedisCommand {
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
			$this->validateEquals(count($arguments), 2);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * SETBIT key offset value ~ Sets or clears the bit at offset in the string value stored at key
	 * 
	 * Sets or clears the bit at offset in the string value stored at key.
	 * The bit is either set or cleared depending on value, which can be either 0 or 1. When key does not exist, a new string value is created. The string is grown to make sure it can hold a bit at offset. The offset 
	 * argument is required to be greater than or equal to 0, and smaller than 232 (this limits bitmaps to 512MB). When the string at key is grown, added bits are set to 0.
	 * 
	 * Warning: When setting the last possible bit (offset equal to 232 -1) and the string value stored at key does not yet hold a string value, or holds a small string value, Redis needs to allocate all intermediate 
	 * memory which can block the server for some time. On a 2010 MacBook Pro, setting bit number 232 -1 (512MB allocation) takes ~300ms, setting bit number 230 -1 (128MB allocation) takes ~80ms, setting bit number 
	 * 228 -1 (32MB allocation) takes ~30ms and setting bit number 226 -1 (8MB allocation) takes ~8ms. Note that once this first allocation is done, subsequent calls to SETBIT for the same key will not have the 
	 * allocation overhead.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 2.1.18
	 * @return int Integer reply: the original bit value stored at offset.
	 * @package Redis\Commands\Strings
	class SETBIT extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 3);
			$this->validateKey($arguments[0]);
			$this->validateInt($arguments[1]);
		}
	}
	 */

	/**
	 * SETEX key seconds value ~ Set the value and expiration of a key
	 * 
	 * Set key to hold the string value and set key to timeout after a given number of seconds. This command is equivalent to executing the following commands:
  	 * SET mykey value
	 * EXPIRE mykey seconds
	 * SETEX is atomic, and can be reproduced by using the previous two commands inside an MULTI/EXEC block. It is provided as a faster alternative to the given sequence of operations, because this operation is 
	 *	    very common when Redis is used as a cache. An error is returned when seconds is invalid.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 2.1.18
	 * @return int Integer reply: the original bit value stored at offset.
	 * @package Redis\Commands\Strings
	 */
	class SETEX extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 3);
			$this->validateKey($arguments[0]);
			$this->validateInt($arguments[1]);
		}
	}

	/**
	 * SETNX key value ~ Set the value of a key, only if the key does not exist
	 * 
	 * Set key to hold string value if key does not exist. In that case, it is equal to SET. When key already holds a value, no operation is performed. SETNX is short for "SET if Not eXists".
	 * 
	 * Design pattern: Locking with SETNX
	 * 
 	 * SETNX can be used as a locking primitive. For example, to acquire the lock of the key foo, the client could try the following:
	 * SETNX lock.foo <current Unix time + lock timeout + 1>
	 * If SETNX returns 1 the client acquired the lock, setting the lock.foo key to the Unix time at which the lock should no longer be considered valid. The client will later use DEL lock.foo in order to release the lock.
	 * If SETNX returns 0 the key is already locked by some other client. We can either return to the caller if it's a non blocking lock, or enter a loop retrying to hold the lock until we succeed or some kind of timeout expires.
	 * Handling deadlocks
 	 * In the above locking algorithm there is a problem: what happens if a client fails, crashes, or is otherwise not able to release the lock? It's possible to detect this condition because the lock key contains a UNIX timestamp. If 
	 * such a  timestamp is equal to the current Unix time the lock is no longer valid.
	 * When this happens we can't just call DEL against the key to remove the lock and then try to issue a SETNX, as there is a race condition here, when multiple clients detected an expired lock and are trying to release it.
	 * C1 and C2 read lock.foo to check the timestamp, because they both received 0 after executing SETNX, as the lock is still held by C3 that crashed after holding the lock.
	 * C1 sends DEL lock.foo
	 * C1 sends SETNX lock.foo and it succeeds
	 * C2 sends DEL lock.foo
	 * C2 sends SETNX lock.foo and it succeeds
	 * ERROR: both C1 and C2 acquired the lock because of the race condition.
	 * Fortunately, it's possible to avoid this issue using the following algorithm. Let's see how C4, our sane client, uses the good algorithm:
	 * C4 sends SETNX lock.foo in order to acquire the lock
	 * The crashed client C3 still holds it, so Redis will reply with 0 to C4.
	 * C4 sends GET lock.foo to check if the lock expired. If it is not, it will sleep for some time and retry from the start.
	 * Instead, if the lock is expired because the Unix time at lock.foo is older than the current Unix time, C4 tries to perform:
	 * GETSET lock.foo <current Unix timestamp + lock timeout + 1>
	 * Because of the GETSET semantic, C4 can check if the old value stored at key is still an expired timestamp. If it is, the lock was acquired.
	 * If another client, for instance C5, was faster than C4 and acquired the lock with the GETSET operation, the C4 GETSET operation will return a non expired timestamp. C4 will simply restart from the first step. Note that even if 
	 * C4 set the key 
	 * a bit a few seconds in the future this is not a problem.
	 * Important note: In order to make this locking algorithm more robust, a client holding a lock should always check the timeout didn't expire before unlocking the key with DEL because client failures can be complex, not just 
	 * crashing but also 
	 * blocking a lot of time against some operations and trying to issue DEL after a lot of time (when the LOCK is already held by another client).
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 2.1.18
	 * @return int Integer reply, specifically: 1 if the key was set, 0 if the key was not set.
	 * @package Redis\Commands\Strings
	 */
	class SETNX extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 2);
			$this->validateKey($arguments[0]);
		}

		function output($line) {
			return intval($line) == 1;
		}
	}

	/**
	 * SETRANGE key offset value ~ Overwrite part of a string at key starting at the specified offset
	 * 
	 * Overwrites part of the string stored at key, starting at the specified offset, for the entire length of value. If the offset is larger than the current length of the string at key, the string is padded with zero-bytes to make 
	 * offset fit. Non-existing keys are considered as empty strings, so this command will make sure it holds a string large enough to be able to set value at offset.
 	 * Note that the maximum offset that you can set is 229 -1 (536870911), as Redis Strings are limited to 512 megabytes. If you need to grow beyond this size, you can use multiple keys. 
	 * Warning: When setting the last possible byte and the string value stored at key does not yet hold a string value, or holds a small string value, Redis needs to allocate all intermediate memory which can block the server for some time. 
	 * On a 2010 MacBook Pro, setting byte number 536870911 (512MB allocation) takes ~300ms, setting byte number 134217728 (128MB allocation) takes ~80ms, setting bit number 33554432 (32MB allocation) takes ~30ms and setting bit number 
	 * 8388608 (8MB allocation) takes ~8ms. Note that once this first allocation is done, subsequent calls to SETRANGE for the same key will not have the allocation overhead.
	 * 
	 * Patterns
	 * Thanks to SETRANGE and the analogous GETRANGE commands, you can use Redis strings as a linear array with O(1) random access. This is a very fast and efficient storage in many real world use cases.
	 * 
	 * Time complexity: O(1), not counting the time taken to copy the new string in place. Usually, this string is very small so the amortized complexity is O(1). Otherwise, complexity is O(M) with M being the length of the value argument.
	 * 
	 * @since: 2.1.18
	 * @return int Integer reply: the original bit value stored at offset.
	 * @package Redis\Commands\Strings
	class SETRANGE extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 3);
			$this->validateKey($arguments[0]);
			$this->validateInt($arguments[1]);
		}
	}
	 */

	/**
	 * STRLEN key ~ Get the length of the value stored in a key
	 * 
	 * Returns the length of the string value stored at key. An error is returned when key holds a non-string value.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 2.1.2
	 * @return int Integer reply: the original bit value stored at offset.
	 * @package Redis\Commands\Strings
	 */
	class STRLEN extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 1);
			$this->validateKey($arguments[0]);
		}
	}