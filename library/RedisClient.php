<?php
	define('CRLF', "\r\n");
	
	class Connection {
		protected $socket = null;
		protected $address = null;
		protected $port = null;
		protected $timeout = 30;
		protected $connected = false;
		
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

		function internals() {
			return array(
				$this->address,
				$this->port,
				$this->timeout,
				$this->socket,
				$this->connected
			);
		}
	}

	/**
	 * Basic command building logic.
	 *
	 * @package Redis
	 */
	abstract class CommandBuilder extends Connection {
		private static $_cache = array();

		/**
		 * __call implements a generic builder interface to send command to the redis server
		 *
		 * @param string $command the spelling of the command to be sent
		 * @param array $arguments additional arguments to the command
		 */
		function __call($command, $arguments) {
			$command = strtoupper($command);
			$command = "{$command}";
			if( !class_exists($command) )
				throw new CommandNotFoundException($command);
			if( !isset(CommandBuilder::$_cache[$command]) )
				CommandBuilder::$_cache[$command] = new $command();
			CommandBuilder::$_cache[$command]->validate($arguments);
			return $this->send(CommandBuilder::$_cache[$command], $arguments);
		}

		/**
		 * Send an unpipelined command (normal, direct mode).
		 */ 
		function send($command, $arguments) { 
			if( !$this->connected )
				throw new NotConnectedException("{$this->address}:{$this->port}");
			fwrite($this->socket, $command->build($arguments));
			if( get_class($command) == 'QUIT' ) {
				// add special handling for the QUIT command because no reading is allowed after it has been issued
				$this->close();
				return;
			} else if( $command->persistent ) {
				return new RedisChannel($command, $arguments, $this);
			} else {
				return $this->read($command);
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
		protected function read($command, $output = true) {
			if( !$this->connected )
				throw new NotConnectedException("{$this->address}:{$this->port}");
			// normal, direct mode
			$line = fgets($this->socket);
			$type = $line[0];
			$line = substr($line, 1, strlen($line) - 3);
			switch($type) {
				case '+':
					// status reply
					if( method_exists($command, 'output') && $output )
						return $command->output($line);
					return ($line == 'OK');
				case ':':
					// integer reply
					if( method_exists($command, 'output') && $output )
						return $command->output($line);
					return intval($line);
				case '$':
					// bulk reply
					$count = intval($line);
					if( $count == -1 )
						return null;
					$line = fgets($this->socket, $count + 3);
					if( method_exists($command, 'output') && $output)
						return $command->output(substr($line, 0, strlen($line) - 2));
					return substr($line, 0, strlen($line) - 2);
				case '*':
					// multi bulk reply
					$count = intval($line);
					$result = null;
					for($i = 0; $i < $count; $i++) {
						$result[] = $this->read($command, false);
					}
					if( $result == null ) {
						// a timeout occured
						// $result = array();
					}
					if( method_exists($command, 'output') && $output )
						return $command->output($result);
					return $result;					
				case '-':
					// error reply
					throw new RedisServerException($line);
				case false:
					throw new RuntimeException("timeout");
				default:
					throw new RuntimeException("received bad reply: '{$type}{$line}'");
			}
		}
	}

	/**
	 * A pipelining wrapper for the RedisClient.
	 *
	 * @package Redis
	 */
	class Pipeline extends CommandBuilder {
		private $pipelining = false;
		private $pipeline = '';
		private $pipeline_count = 0;
		private $pipeline_amount = 0;
		private $client = null;

		/**
		 * Basic constructor.
		 *
		 * @param int $size the size of the batches to be sent
		 * @param RedisClient $client the underlying un-pipelined connection
		 */
		function __construct($size, $client) {
			$this->pipelining = true;
			$this->pipeline_amount = $size;
			$this->client = $client;
		}

		/**
		 * Flushes any remaining commands that were buffered for pipelining mode.
		 */ 
		function flush() {
			if( !$this->pipelining )
				throw new CannotFlushDirectConnectionException("{$this->address}:{$this->port}");
			if( $this->pipeline_count > 0 ) {
				fwrite($this->client->socket, $this->pipeline);
				$this->pipeline = '';
				$this->pipeline_count = 0;
			}
		}

		/**
		 * Send an pipelined command 
		 */ 
		function send($command, $arguments) {
			if( !$this->client->connected )
				throw new NotConnectedException("{$this->client->address}:{$this->client->port}");
			if( !$this->pipelining )
				throw new RuntimeException('cannot send pipelined commands on normal connection');
			$this->pipeline .= $command->build($arguments);
			$this->pipeline_count += 1;
			if( $this->pipeline_count == $this->pipeline_amount ) {
				fwrite($this->client->socket, $this->pipeline);
				$this->pipeline = '';
				$this->pipeline_count = 0;
			}
			return true;
		}
	}
	
	class Cluster extends CommandBuilder {
	}

	/**
	 * This class acts as a single connection to a redis instance.
	 *
	 * @package Redis
	 */
	class RedisClient extends CommandBuilder {
		/**
		 * Allows the client to be put into pipeline mode. 
		 * 
		 * @param int $size the size of the batches to use
		 * @returns a Pipeline instance that can be used to send commands
		 */ 
		function pipeline($size) {
			 $pipeline = new Pipeline($size, $this);
			 return $pipeline;
		}
	}

	/**
	 * This class retrieves messages for the persistent commands.
	 *
	 * @package Redis
	 */
	class RedisChannel extends CommandBuilder {
		function __construct($command, $arguments, $client) {
			set_time_limit(0);
			$this->client = $client;
			$this->command = $command;
			list(
				$this->address,
				$this->port,
				$this->timeout,
				$this->socket,
				$this->connected
			) = $client->internals();
			$line = $this->read($this->command);
			/* assume its correct for now
			array_shift($line);
			$line = array_chunk($line, 2);
			$result = array();
			foreach($line as $_line) {
				$result[$_line[0]] = $_line[1];
			}
			*/
		}

		public function wait() {
			while($this->connected()) {
				try {
					return $this->read($this->command, -1);
				} catch( RedisChannelTimeoutException $e ) {
					// ok restart 
				} catch( Exception $e ) {
					// something went wrong, close connection
					$this->close();
				}
			}
		}

		/**
		 * override __call to disallow non-subscribed mode calls as per the documentation of SUBSCRIBE.
		 *
		 * @param string $command the spelling of the command to be sent
		 * @param array $arguments additional arguments to the command
		 */
		function __call($command, $arguments) {
			if( !in_array(strtoupper($command), array('SUBSCRIBE', 'PSUBSCRIBE', 'UNSUBSCRIBE', 'PUNSUBSCRIBE')) ) {
				throw new RuntimeException('Command not allowed in subscribed mode.');
			}
			return parent::__call($command, $arguments);
		}

	}
