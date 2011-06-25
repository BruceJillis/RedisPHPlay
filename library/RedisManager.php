<?php
	/**
	 * RedisManager acts as a facade for the PHP-Redis-Fast library. It supports creating connections and sets up the PHP environment for redis type io.
	 * 
	 * This class sets the time limit to 0.
	 * @package Redis
	 */
	class RedisManager {
		private $clients = array();
		
		/**
		 * Basic constructor. 
		 */
		function __construct() {			
		}

		/**
		 * This method attempts to connect to a redis instance. 
		 *
		 * A connection will never be opened twice, instead the already open connection will be returned.
		 * @throws CouldNotConnectException if the connection could not be established
		 */
		function connect($address, $port = 6379) {
			$key = "{$address}:{$port}";
			if( !isset($this->clients[$key]) ) {
				$this->clients[$key] = new RedisClient($address, $port);
				try {
					$this->clients[$key]->open();
				} catch(Exception $e) {
					if( !$this->clients[$key]->connected() ) {
						unset($this->clients[$key]);
						throw new CouldNotConnectException($key);
					}
				}
			}
			return $this->clients[$key];
		}

		function cluster() {			 
			return $this;
		}
	}