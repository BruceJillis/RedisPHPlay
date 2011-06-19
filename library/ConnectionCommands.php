<?php
	/**
	 * AUTH password ~ Authenticate to the server
	 * 
	 * Request for authentication in a password protected Redis server. Redis can be instructed to require a password before allowing clients to execute commands. This is done using the requirepass directive in the 
	 * configuration file. If password matches the password in the configuration file, the server replies with the OK status code and starts accepting commands. Otherwise, an error is returned and the clients needs to 
	 * try a new password. 
	 * 
	 * Note: because of the high performance nature of Redis, it is possible to try a lot of passwords in parallel in very short time, so make sure to generate a strong and very long password so that this attack is infeasible.
	 * 
	 * @since: 0.0.8
	 * @return string Status code reply
	 * @package Redis\Commands\Connection
	 */
	class AUTH extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 1);
		}
	}

	/**
	 * ECHO message ~ Echo the given string
	 * 
	 * Returns message.
	 * 
	 * @since: 0.0.7
	 * @return array Bulk reply
	 * @package Redis\Commands\Connection
	 */
	class _ECHO extends RedisCommand {
		function __construct() {
			$this->name = 'ECHO';
		}

		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 1);
		}
	}

	/**
	 * PING ~ Ping the server
	 * 
	 * Returns PONG. This command is often used to test if a connection is still alive, or to measure latency.
	 * 
	 * @since: 0.0.7
	 * @return string Status code reply: PONG
	 * @package Redis\Commands\Connection
	 */
	class PING extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 0);
		}

		function output($line) {
			return $line;
		}
	}

	/**
	 * QUIT ~ Close the connection
	 * 
	 * Ask the server to close the connection. The connection is closed as soon as all pending replies have been written to the client.
	 * 
	 * @since: 0.0.7
	 * @return string Status code reply: always OK.
	 * @package Redis\Commands\Connection
	 */
	class QUIT extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 0);
		}
	}

	/**
	 * SELECT index ~ Change the selected database for the current connection
	 * 
	 * Select the DB with having the specified zero-based numeric index. New connections always use DB 0.
	 * 
	 * @since: 0.0.7
	 * @return string Status code reply
	 * @package Redis\Commands\Connection
	 */
	class SELECT extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 1);
			$this->validateInt($arguments[0]);
		}
	}