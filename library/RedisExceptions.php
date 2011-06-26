<?php
	/**
	 * Base class for all exceptions of the PHP Redis Fast library.
	 * 
	 * @package Redis\Exceptions
	 */
	class RedisException extends Exception {
	}
	
	/**
	 * Thrown when the requested address could not be reached.
	 * 
	 * @package Redis\Exceptions
	 */
	class CouldNotConnectException extends RedisException {
		function __construct($address, $additional = '') {
			if( $additional != '' )
				$additional = "($additional)";
			parent::__construct("could not connect {$additional} to {$address}", 1000);
		}
	}
	/**
	 * Thrown when a channel socket times out.
	 * 
	 * @package Redis\Exceptions
	 */
	class RedisChannelTimeoutException extends RedisException {
	}

	/**
	 * Thrown when redis replies with an error reply
	 * 
	 * @package Redis\Exceptions
	 */
	class RedisServerException extends RedisException {
		function __construct($line) {
			parent::__construct("redis error reply: $line", 1001);
		}
	}

	/**
	 * Thrown if a connection is openend when it has already is connected.
	 * 
	 * @package Redis\Exceptions
	 */
	class AlreadyConnectedException extends RedisException {
		function __construct($server) {
			parent::__construct("already connected to {$server}", 1002);
		}
	}

	/**
	 * Thrown if a connection is closed that is not open
	 * 
	 * @package Redis\Exceptions
	 */
	class NotConnectedException extends RedisException {
		function __construct($server) {
			parent::__construct("closing an unopened connection to {$server}", 1003);
		}
	}

	/**
	 * Thrown if a connection cannot be closed
	 * 
	 * @package Redis\Exceptions
	 */
	class CouldNotCloseException extends RedisException {
		function __construct($server) {
			parent::__construct("error during closing of connection to {$server}", 1004);
		}
	}

	/**
	 * Thrown if a direct (not pipelined) connection is flushed
	 * 
	 * @package Redis\Exceptions
	 */
	class CannotFlushDirectConnectionException extends RedisException {
		function __construct($server) {
			parent::__construct("trying to flush direct (not pipelined) connection to {$server}", 1005);
		}
	}

	/**
	 * Thrown if a command isn't found
	 * 
	 * @package Redis\Exceptions
	 */
	class CommandNotFoundException extends RedisException {
		function __construct($name) {
			parent::__construct("command '{$name}' not found", 100);
		}
	}