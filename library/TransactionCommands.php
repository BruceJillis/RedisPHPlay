<?php
	/**
	 * DISCARD ~ Discard all commands issued after MULTI
	 * 
	 * Flushes all previously queued commands in a transaction and restores the connection state to normal. If WATCH was used, DISCARD unwatches all keys.
	 * 
	 * @since: 1.3.3
	 * @return boolean Status code reply: always OK.
	 * @package Redis\Commands\Transactions
	 */
	class DISCARD extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 0);
		}
	}

	/**
	 * EXEC ~ Execute all commands issued after MULTI
	 * 
	 * Executes all previously queued commands in a transaction and restores the connection state to normal. When using WATCH, EXEC will execute commands only if the watched keys were not modified, 
	 * allowing for a check-and-set mechanism.
	 * 
	 * @since: 1.1.95
	 * @return array Multi-bulk reply: each element being the reply to each of the commands in the atomic transaction. When using WATCH, EXEC can return a Null multi-bulk reply if the execution was aborted.
	 * @package Redis\Commands\Transactions
	 */
	class EXEC extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 0);
		}
	}

	/**
	 * MULTI ~ Mark the start of a transaction block
	 * 
	 * Marks the start of a transaction block. Subsequent commands will be queued for atomic execution using EXEC.
	 * 
	 * @since: 1.1.95
	 * @return boolean Status code reply: always OK.
	 * @package Redis\Commands\Transactions
	 */
	class MULTI extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 0);
		}
	}

	/**
	 * UNWATCH ~ Forget about all watched keys
	 * 
	 * Flushes all the previously watched keys for a transaction. If you call EXEC or DISCARD, there's no need to manually call UNWATCH.
	 *
	 * Time complexity: O(1).
	 * 
	 * @since: 2.1.0
	 * @return boolean Status code reply: always OK.
	 * @package Redis\Commands\Transactions
	 */
	class UNWATCH extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 0);
		}
	}

	/**
	 * WATCH key [key ...] ~ Watch the given keys to determine execution of the MULTI/EXEC block
	 * 
	 * Marks the given keys to be watched for conditional execution of a transaction.
	 *
	 * Time complexity: O(1) for every key.
	 * 
	 * @since: 2.1.0
	 * @return boolean Status code reply: always OK.
	 * @package Redis\Commands\Transactions
	 */
	class WATCH extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThenEqual(count($arguments), 1);
			foreach($arguments as $argument)
				$this->validateKey($argument);

		}
	}