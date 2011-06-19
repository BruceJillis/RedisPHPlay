<?php
	define('CRLF', "\r\n");

	/**
	 * This class acts as a single connection to a redis instance.
	 *
	 * @package Redis
	 */
	class RedisClient {
		private $address = null;
		private $port = null;
		private $timeout = 30;
		private $connected = false;
		private $pipelining = false;
		private $pipeline = '';
		private $pipeline_count = 0;
		private $pipeline_amount = 0;
		
		/**
		 * Basic constructor.
		 *
		 * @param string $address the hostname of the redis instance 
		 * @param string $port the port at which the redis instance is reachable (default 6379)
		 * @param int $timeout the timeout of the underlying socket (default 30)
		 */
		function __construct($address, $port = 6379, $timeout = 30) {
			$this->address = $address;
			$this->port = $port;
			$this->timeout = $timeout;
		}
		
		/**
		 * Simple getter for connection status
		 *
		 * @return boolean true if connected, false otherwise
		 */ 
		function connected() {
			return $this->connected;
		}

		/**
		 * Allows the client to be put into pipeline mode. 
		 * 
		 * @param int $size the size of the batches to use
		 */ 
		function pipeline($size) {
			$this->pipelining = true;
			$this->pipeline_amount = $size;
		}

		/**
		 * Flushes any remaining commands that were buffered for pipelining mode.
		 */ 
		function flush() {
			if( !$this->pipelining )
				throw new CannotFlushDirectConnectionException("{$this->address}:{$this->port}");
			if( $this->pipeline_count > 0 ) {
				fwrite($this->socket, $this->pipeline);
				$this->pipeline = '';
				$this->pipeline_count = 0;
			}
		}

		/**
		 * Open a connection.
		 *
		 * A connection can never be opened twice
		 */
		function open() {
			if( $this->connected )
				throw new AlreadyConnectedException("{$this->address}:{$this->port}");
			$errno = null;
			$errstr = null;
			try {
				$this->socket = fsockopen($this->address, $this->port, $errno, $errstr, $this->timeout);
				if( !$this->socket )
					throw new CouldNotConnectException("{$this->address}:{$this->port}", $errstr);
			} catch(Exception $e) {
				throw new CouldNotConnectException("{$this->address}:{$this->port}", $e->getMessage());
			}
			$this->connected = true;
		}

		/**
		 * Close a connection.
		 *
		 * A unopened connection cannot be closed
		 */
		function close() {
			if( !$this->connected )
				throw new NotConnectedException("{$this->address}:{$this->port}");
			// close the socket
			if( !fclose($this->socket) )
				throw new CouldNotCloseException("{$this->address}:{$this->port}");
			$this->connected = false;
		}

		/**
		 * __call implements a generic builder interface to send command to the redis server
		 *
		 * @param string $command the spelling of the command to be sent
		 * @param array $arguments additional arguments to the command
		 */
		function __call($command, $arguments) {
			if( !$this->connected )
				throw new NotConnectedException("{$this->address}:{$this->port}");
			$command = strtoupper($command);
			$command = "{$command}";
			if( !class_exists($command) )
				throw new CommandNotFoundException($command);
			if( !isset($this->_cache[$command]) )
				$this->_cache[$command] = new $command();
			$this->_cache[$command]->validate($arguments);
			if( $this->pipelining ) {
				// pipelined mode
				$this->pipeline .= $this->_cache[$command]->build($arguments);
				$this->pipeline_count += 1;
				if( $this->pipeline_count == $this->pipeline_amount ) {
					fwrite($this->socket, $this->pipeline);
					$this->pipeline = '';
					$this->pipeline_count = 0;
				}
			} else {
				// normal, direct mode
				fwrite($this->socket, $this->_cache[$command]->build($arguments));
				return $this->read($this->_cache[$command]);
			}
		}

		/**
		 * Internal method to read a reply from the redis server
		 *
		 * @param object $command an instance of the command that was sent
		 * @returns mixed the appropriate response according to the command that caused it
		 * @throws NotConnectedException if the client is not connected
		 * @throws RedisServerException if the client recieves an error reply from the redis server
		 * @throws RuntimeException if the client recieved a response that was not understood
		 */
		private function read($command) {
			if( !$this->connected )
				throw new NotConnectedException("{$this->address}:{$this->port}");
			// normal, direct mode
			$line = fgets($this->socket);
			$type = $line[0];
			$line = substr($line, 1, strlen($line) - 3);
			switch($type) {
				case '+':
					// status reply
					if( method_exists($command, 'output') )
						return $command->output($line);
					return ($line == 'OK');
				case ':':
					// integer reply
					if( method_exists($command, 'output') )
						return $command->output($line);
					return intval($line);
				case '$':
					// bulk reply
					$count = intval($line);
					if( $count == -1 )
						return null;
					$line = fgets($this->socket, $count + 3);
					if( method_exists($command, 'output') )
						return $command->output(substr($line, 0, strlen($line) - 2));
					return substr($line, 0, strlen($line) - 2);
				case '*':
					// multi bulk reply
					$count = intval($line);
					for($i = 0; $i < $count; $i++) {
						$result[] = $this->read($command);
					}
					return $result;					
				case '-':
					// error reply
					throw new RedisServerException($line);
				default:
					throw new RuntimeException("received bad reply: '{$type}{$line}'");
			}
		}
	}