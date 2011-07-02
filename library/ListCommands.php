<?php
	/**
	 * BLPOP key [key ...] timeout ~ Remove and get the first element in a list, or block until one is available
	 * 
	 * BLPOP is a blocking list pop primitive. It is the blocking version of LPOP because it blocks the connection when there are no elements to pop from any of the given lists. An element is popped from the head of the 
	 * first list that is non-empty, with the given keys being checked in the order that they are given.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 1.3.1
	 * @return array Multi-bulk reply: specifically: A nil multi-bulk when no element could be popped and the timeout expired. A two-element multi-bulk with the first element being the name of the key where an element 
	 * was popped and the second element being the value of the popped element.
	 * @package Redis\Commands\Lists
	 */
	class BLPOP extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThen(count($arguments), 1);
			// todo: validation
		}

		function output($array) {
			if( $array == null )
				return null;
			$array = array_chunk($array, 2);
			$result = array();
			foreach($array as $value) {
				$result[$value[0]] = $value[1];
			}
			return $result;
		}
	}

	/**
	 * BRPOP key [key ...] timeout ~ Remove and get the last element in a list, or block until one is available
	 * 
	 * BRPOP is a blocking list pop primitive. It is the blocking version of RPOP because it blocks the connection when there are no elements to pop from any of the given lists. An element is popped from the tail of the first list 
	 * that is non-empty, with the given keys being checked in the order that they are given.See BLPOP for the exact semantics. BRPOP is identical to BLPOP, apart from popping from the tail of a list instead of the head of a list.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 1.3.1
	 * @return array Multi-bulk reply: specifically: A nil multi-bulk when no element could be popped and the timeout expired. A two-element multi-bulk with the first element being the name of the key where an element was popped and 
	 * the second element being the value of the popped element.
	 * @package Redis\Commands\Lists
	 */
	class BRPOP extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThen(count($arguments), 1);
			// todo: validation
		}

		function output($array) {
			if( $array == null )
				return null;
			$array = array_chunk($array, 2);
			$result = array();
			foreach($array as $value) {
				$result[$value[0]] = $value[1];
			}
			return $result;
		}
	}

	/**
	 * BRPOPLPUSH source destination timeout ~ Pop a value from a list, push it to another list and return it; or block until one is available
	 * 
	 * BRPOPLPUSH is the blocking variant of RPOPLPUSH. When source contains elements, this command behaves exactly like RPOPLPUSH. When source is empty, Redis will block the connection until another client pushes to it or until 
	 * timeout is reached. A timeout of zero can be used to block indefinitely. See RPOPLPUSH for more information.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 2.1.7
	 * @return array Bulk reply: the element being popped from source and pushed to destination. If timeout is reached, a Null multi-bulk reply is returned.
	 * @package Redis\Commands\Lists
	 */
	class BRPOPLPUSH extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThen(count($arguments), 1);
			// todo: validation
		}
	}

	/**
	 * LINDEX key index ~ Get an element from a list by its index
	 * 
	 * Returns the element at index index in the list stored at key. The index is zero-based, so 0 means the first element, 1 the second element and so on. Negative indices can be used to designate elements starting at the tail of the list. 
	 * Here, -1 means the last element, -2 means the penultimate and so forth.
	 * When the value at key is not a list, an error is returned.
	 * 
	 * Time complexity: O(N) where N is the number of elements to traverse to get to the element at index. This makes asking for the first or the last element of the list O(1).
	 * 
	 * @since: 0.07
	 * @return array Bulk reply: the requested element, or nil when index is out of range.
	 * @package Redis\Commands\Lists
	 */
	class LINDEX extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 2);
			$this->validateKey($arguments[0]);
			$this->validateInt($arguments[1]);
		}
	}

	/**
	 * LINSERT key BEFORE|AFTER pivot value ~ Insert an element before or after another element in a list
	 * 
	 * Inserts value in the list stored at key either before or after the reference value pivot.
	 * When key does not exist, it is considered an empty list and no operation is performed.
	 * An error is returned when key exists but does not hold a list value.
	 * 
	 * Time complexity: O(N) where N is the number of elements to traverse before seeing the value pivot. This means that inserting somewhere on the left end on the list (head) can be considered O(1) and inserting somewhere 
	 * on the right end (tail) is O(N).
	 * 
	 * @since: 2.1.1
	 * @return int Integer reply: the length of the list after the insert operation, or -1 when the value pivot was not found.
	 * @package Redis\Commands\Lists
	 */
	class LINSERT extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 4);
			$this->validateKey($arguments[0]);
			$arguments[1] = strtoupper($arguments[1]);
			$this->validateEnumerate($arguments[1], array('BEFORE', 'AFTER'));
		}
	}

	/**
	 * LLEN key ~ Get the length of a list
	 * 
	 * Returns the length of the list stored at key. If key does not exist, it is interpreted as an empty list and 0 is returned. An error is returned when the value stored at key is not a list.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.07
	 * @return int array Bulk reply: the value of the first element, or nil when key does not exist.
	 * @package Redis\Commands\Lists
	 */
	class LLEN extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 1);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * LPOP key ~ Remove and get the first element in a list
	 * 
	 * Removes and returns the first element of the list stored at key.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.07
	 * @return array Bulk reply: the value of the first element, or nil when key does not exist.
	 * @package Redis\Commands\Lists
	 */
	class LPOP extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 1);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * LPUSH key value ~ Prepend a value to a list
	 * 
	 * Inserts value at the head of the list stored at key. If key does not exist, it is created as empty list before performing the push operation. When key holds a value that is not a list, an error is returned.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.07
	 * @return int Integer reply: the length of the list after the push operation.
	 * @package Redis\Commands\Lists
	 */
	class LPUSH extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 2);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * LPUSHX key value ~ Prepend a value to a list, only if the list exists
	 * 
	 * Inserts value at the head of the list stored at key, only if key already exists and holds a list. In contrary to LPUSH, no operation will be performed when key does not yet exist.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 2.1.1
	 * @return int Integer reply: the length of the list after the push operation.
	 * @package Redis\Commands\Lists
	 */
	class LPUSHX extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 2);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * LRANGE key start stop ~ Get a range of elements from a list
	 * 
	 * Returns the specified elements of the list stored at key. The offsets start and end are zero-based indexes, with 0 being the first element of the list (the head of the list), 1 being the next element and so on.
	 * These offsets can also be negative numbers indicating offsets starting at the end of the list. For example, -1 is the last element of the list, -2 the penultimate, and so on.
	 * 
	 * Time complexity: O(S+N) where S is the start offset and N is the number of elements in the specified range.
	 * 
	 * @since: 0.07
	 * @return array Multi-bulk reply: list of elements in the specified range.
	 * @package Redis\Commands\Lists
	 */
	class LRANGE extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 3);
			$this->validateKey($arguments[0]);
			$this->validateInt($arguments[1]);
			$this->validateInt($arguments[2]);
		}

		function output($list) {
			return $list;
		}
	}

	/**
	 * LREM key count value ~ Remove elements from a list
	 * 
	 * Removes the first count occurrences of elements equal to value from the list stored at key. The count argument influences the operation in the following ways:
	 * count > 0: Remove elements equal to value moving from head to tail.
	 * count < 0: Remove elements equal to value moving from tail to head.
	 * count = 0: Remove all elements equal to value.
	 * For example, LREM list -2 "hello" will remove the last two occurrences of "hello" in the list stored at list.
	 * Note that non-existing keys are treated like empty lists, so when key does not exist, the command will always return 0.
	 * 
	 * Time complexity: O(N) where N is the length of the list.
	 * 
	 * @since: 0.07
	 * @return int Integer reply: the number of removed elements.
	 * @package Redis\Commands\Lists
	 */
	class LREM extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 3);
			$this->validateKey($arguments[0]);
			$this->validateInt($arguments[1]);
		}
	}

	/**
	 * LSET key index value ~ Set the value of an element in a list by its index
	 * 
	 * Sets the list element at index to value. For more information on the index argument, see LINDEX.
	 * An error is returned for out of range indexes.
	 * 
	 * Time complexity: O(N) where N is the length of the list. Setting either the first or the last element of the list is O(1).
	 * 
	 * @since: 0.07
	 * @return boolean Status code reply
	 * @package Redis\Commands\Lists
	 */
	class LSET extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 3);
			$this->validateKey($arguments[0]);
			$this->validateInt($arguments[1]);
		}
	}

	/**
	 * LTRIM key start stop ~ Trim a list to the specified range
	 * 
	 * Trim an existing list so that it will contain only the specified range of elements specified. Both start and stop are zero-based indexes, where 0 is the first element of the list (the head), 1 the next element and so on.
	 * 
	 * Time complexity: O(N) where N is the number of elements to be removed by the operation.
	 * 
	 * @since: 0.07
	 * @return boolean Status code reply
	 * @package Redis\Commands\Lists
	 */
	class LTRIM extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 3);
			$this->validateKey($arguments[0]);
			$this->validateInt($arguments[1]);
			$this->validateInt($arguments[2]);
		}
	}

	/**
	 * RPOP key ~ Remove and get the last element in a list
	 * 
	 * Removes and returns the last element of the list stored at key.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.07
	 * @return array Bulk reply: the value of the last element, or nil when key does not exist.
	 * @package Redis\Commands\Lists
	 */
	class RPOP extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 1);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * RPOPLPUSH source destination ~ Remove the last element in a list, append it to another list and return it
	 * 
	 * Atomically returns and removes the last element (tail) of the list stored at source, and pushes the element at the first element (head) of the list stored at destination.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 1.1
	 * @return array Bulk reply: the element being popped and pushed.
	 * @package Redis\Commands\Lists
	 */
	class RPOPLPUSH extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 2);
			$this->validateKey($arguments[0]);
			$this->validateKey($arguments[1]);
		}
	}

	/**
	 * RPUSH key value ~ Append a value to a list
	 * 
	 * Inserts value at the tail of the list stored at key. If key does not exist, it is created as empty list before performing the push operation. When key holds a value that is not a list, an error is returned.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 0.07
	 * @return int Integer reply: the length of the list after the push operation.
	 * @package Redis\Commands\Lists
	 */
	class RPUSH extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 2);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * RPUSHX key value ~ Append a value to a list, only if the list exists
	 * 
	 * Inserts value at the tail of the list stored at key, only if key already exists and holds a list. In contrary to RPUSH, no operation will be performed when key does not yet exist.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 2.1.1
	 * @return int Integer reply: the length of the list after the push operation.
	 * @package Redis\Commands\Lists
	 */
	class RPUSHX extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 2);
			$this->validateKey($arguments[0]);
		}
	}
