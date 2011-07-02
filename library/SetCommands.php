<?php
	/**
	 * SADD key member [member ...] ~ Add a member to a set
	 * 
	 * Add member to the set stored at key. If member is already a member of this set, no operation is performed. If key does not exist, a new set is created with member as its sole member.
	 * An error is returned when the value stored at key is not a set.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.07 up until Redis 2.3, SADD accepted a single member.
	 * @return int Integer reply: the number of elements actually added to the set.
	 * @package Redis\Commands\Sets
	 */
	class SADD extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThenEqual(count($arguments), 2);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * SCARD key ~ Get the number of members in a set
	 * 
	 * Returns the set cardinality (number of elements) of the set stored at key.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.07 
	 * @return int Integer reply: the cardinality (number of elements) of the set, or 0 if key does not exist.
	 * @package Redis\Commands\Sets
	 */
	class SCARD extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 1);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * SDIFF key [key ...] ~ Subtract multiple sets
	 * 
	 * Returns the members of the set resulting from the difference between the first set and all the successive sets. Keys that do not exist are considered to be empty sets.
	 * 
	 * Time complexity: O(N) where N is the total number of elements in all given sets.
	 * 
	 * @since: 0.100
	 * @return array Multi-bulk reply: list with members of the resulting set.
	 * @package Redis\Commands\Sets
	 */
	class SDIFF extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThen(count($arguments), 1);
			foreach($arguments as $argument)
				$this->validateKey($argument);
		}
	}

	/**
	 * SDIFFSTORE destination key [key ...] ~ Subtract multiple sets and store the resulting set in a key
	 * 
	 * This command is equal to SDIFF, but instead of returning the resulting set, it is stored in destination. If destination already exists, it is overwritten.
	 * 
	 * Time complexity: O(N) where N is the total number of elements in all given sets.
	 * 
	 * @since: 0.100
	 * @return int Integer reply: the number of elements in the resulting set.
	 * @package Redis\Commands\Sets
	 */
	class SDIFFSTORE extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThenEqual(count($arguments), 2);
			$this->validateKey($arguments[0]);
			$this->validateKey($arguments[1]);
		}
	}

	/**
	 * SINTER key [key ...] ~ Intersect multiple sets
	 * 
	 * Returns the members of the set resulting from the intersection of all the given sets. Keys that do not exist are considered to be empty sets. With one of the keys being an empty set, the resulting set 
	 * is also empty (since set intersection with an empty set always results in an empty set).
	 * 
	 * Time complexity: O(N*M) worst case where N is the cardinality of the smallest set and M is the number of sets.
	 * 
	 * @since: 0.07
	 * @return array Multi-bulk reply: list with members of the resulting set.
	 * @package Redis\Commands\Sets
	 */
	class SINTER extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThen(count($arguments), 1);
			foreach($arguments as $argument)
				$this->validateKey($argument);
		}
	}

	/**
	 * SINTERSTORE destination key [key ...] ~ Intersect multiple sets and store the resulting set in a key
	 * 
	 * This command is equal to SINTER, but instead of returning the resulting set, it is stored in destination. If destination already exists, it is overwritten.
	 * 
	 * Time complexity: O(N*M) worst case where N is the cardinality of the smallest set and M is the number of sets.
	 * 
	 * @since: 0.100
	 * @return int Integer reply: the number of elements in the resulting set.
	 * @package Redis\Commands\Sets
	 */
	class SINTERSTORE extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThenEqual(count($arguments), 2);
			$this->validateKey($arguments[0]);
			for($i = 1; $i < count($arguments); $i++)
				$this->validateKey($arguments[$i]);
		}
	}

	/**
	 * SISMEMBER key member ~ Determine if a given value is a member of a set
	 * 
	 * Returns if member is a member of the set stored at key.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.07 
	 * @return int Integer reply, specifically: 1 if the element is a member of the set. 0 if the element is not a member of the set, or if key does not exist.
	 * @package Redis\Commands\Sets
	 */
	class SISMEMBER extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 2);
			$this->validateKey($arguments[0]);
			$this->validateKey($arguments[1]);
		}

		function output($line) {
			return intval($line) == 1;
		}
	}

	/**
	 * SMEMBERS key ~ Get all the members in a set
	 * 
	 * Returns all the members of the set value stored at key. This has the same effect as running SINTER with one argument key.
	 * 
	 * Time complexity: O(N) where N is the set cardinality.
	 * 
	 * @since: 0.07 
	 * @return array Multi-bulk reply: all elements of the set.
	 * @package Redis\Commands\Sets
	 */
	class SMEMBERS extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 1);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * SMOVE source destination member ~ Move a member from one set to another
	 * 
	 * Move member from the set at source to the set at destination. This operation is atomic. In every given moment the element will appear to be a member of source or destination for other clients.
	 * If the source set does not exist or does not contain the specified element, no operation is performed and 0 is returned. Otherwise, the element is removed from the source set and added to the destination set. When the 
	 * specified element already exists in the destination set, it is only removed from the source set.
	 * An error is returned if source or destination does not hold a set value.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.091
	 * @return int Integer reply, specifically: 1 if the element is moved. 0 if the element is not a member of source and no operation was performed.
	 * @package Redis\Commands\Sets
	 */
	class SMOVE extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 3);
			$this->validateKey($arguments[0]);
			$this->validateKey($arguments[1]);
			$this->validateKey($arguments[2]);
		}
	}

	/**
	 * SPOP key ~ Remove and return a random member from a set
	 * 
	 * Removes and returns a random element from the set value stored at key. This operation is similar to SRANDMEMBER, that returns a random element from a set but does not remove it.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.101
	 * @return mixed Bulk reply: the removed element, or nil when key does not exist.
	 * @package Redis\Commands\Sets
	 */
	class SPOP extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 1);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * SRANDMEMBER key ~ Get a random member from a set
	 * 
	 * Return a random element from the set value stored at key.
	 * This operation is similar to SPOP, however while SPOP also removes the randomly selected element from the set, SRANDMEMBER will just return a random element without altering the original set in any way.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 1.001
	 * @return mixed Bulk reply: the removed element, or nil when key does not exist.
	 * @package Redis\Commands\Sets
	 */
	class SRANDMEMBER extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 1);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * SREM key member ~ Remove a member from a set
	 * 
	 * Return a random element from the set value stored at key.
	 * This operation is similar to SPOP, however while SPOP also removes the randomly selected element from the set, SRANDMEMBER will just return a random element without altering the original set in any way.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.07
	 * @return int Integer reply, specifically: 1 if the element was removed. 0 if the element was not a member of the set.
	 * @package Redis\Commands\Sets
	 */
	class SREM extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 2);
			$this->validateKey($arguments[0]);
			$this->validateKey($arguments[1]);
		}
	}

	/**
	 * SUNION key [key ...] ~ Add multiple sets
	 * 
	 * Return a random element from the set value stored at key.
	 * 
	 * Time complexity: O(N) where N is the total number of elements in all given sets.
	 * 
	 * @since: 0.091
	 * @return array Multi-bulk reply: list with members of the resulting set.
	 * @package Redis\Commands\Sets
	 */
	class SUNION extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThenEqual(count($arguments), 1);
			foreach($arguments as $argument)
				$this->validateKey($argument);
		}
	}

	/**
	 * SUNIONSTORE destination key [key ...] ~ Add multiple sets and store the resulting set in a key
	 * 
	 * This command is equal to SUNION, but instead of returning the resulting set, it is stored in destination. If destination already exists, it is overwritten.
	 * 
	 * Time complexity: O(N) where N is the total number of elements in all given sets.
	 * 
	 * @since: 0.091
	 * @return int Integer reply: the number of elements in the resulting set.
	 * @package Redis\Commands\Sets
	 */
	class SUNIONSTORE extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThenEqual(count($arguments), 2);
			foreach($arguments as $argument)
				$this->validateKey($argument);
		}
	}