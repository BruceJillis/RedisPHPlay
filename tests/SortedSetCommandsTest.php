<?php
	require '..\library\RedisPHPlay.php';
	
	/**
	 * Test for the sorted set commands ( http://redis.io/commands#sorted_set ).
	 * 
	 * @package Redis\Tests
	 */
	class SortedSetCommandsTest extends PHPUnit_Framework_TestCase {
		/**
		 * test setUp, create a RedisManager in $this->redis
		 */	
		public function setUp() {
			$this->redis = new RedisManager();
		}

		/**
		 * this methods connects and AUTH's at the database
		 */	
		function connect() {
			$client = $this->redis->connect('127.0.0.1', 6379);
			$this->assertTrue($client->connected());
			$this->assertTrue($client->AUTH('test'));
			return $client;
		}
	}