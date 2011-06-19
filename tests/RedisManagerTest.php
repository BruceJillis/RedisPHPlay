<?php
	require '..\library\RedisPHPlay.php';
	
	echo "THIS TEST REQUIRES A REDIS ON 127.0.0.1:6379!\n";
	
	/**
	 * Test for the RedisManager.
	 * 
	 * @package Redis\Tests
	 */
	class RedisManagerTest extends PHPUnit_Framework_TestCase {
		/**
		 * test setUp, create a RedisManager in $this->redis
		 */	
		public function setUp() {
			$this->redis = new RedisManager();
		}

		/**
		 * Testing basic open/close functionality
		 */		
		public function testOpenCloseGoodConnection() {
			$client = $this->redis->connect('127.0.0.1', 6379);
			$this->assertTrue($client->connected());
			$client->close();
			$this->assertFalse($client->connected());
		}

		/**
		 * Testing basic open/close functionality
		 * @expectedException CouldNotConnectException
		 */		
		public function testBadPort() {
			$client = $this->redis->connect('127.0.0.1', 0);
			$this->assertTrue($client->connected());
			$client->close();
			$this->assertFalse($client->connected());
		}
	}