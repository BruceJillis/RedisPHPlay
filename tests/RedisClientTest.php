<?php
	require '..\library\RedisPHPlay.php';
	
	/**
	 * Test for the RedisClient.
	 * 
	 * @package Redis\Tests
	 */
	class RedisClientTest extends PHPUnit_Framework_TestCase {
		/**
		 * test setUp, create a RedisManager in $this->redis
		 */	
		public function setUp() {
			$this->redis = new RedisManager();
		}

		function connect() {
			$client = $this->redis->connect('127.0.0.1', 6379);
			$this->assertTrue($client->connected());
			$this->assertTrue($client->AUTH('test'));
			return $client;
		}

		/**
		 * Testing basic open/close functionality
		 */		
		public function testOpenCloseGoodConnection() {
			$client = $this->connect();
			$this->assertEquals($client->PING(), "PONG");
			$this->assertEquals($client->_ECHO("boo"), "boo");
			$this->assertEquals($client->_ECHO("boo12"), "boo12");
			$this->assertEquals($client->_ECHO("boo1234"), "boo1234");
			$client->close();
			$this->assertFalse($client->connected());
		}

		/**
		 * Testing unknown command
		 * @expectedException CommandNotFoundException
		 */		
		public function testUnknownCommand() {
			$client = $this->connect();
			$client->UNKNOWN();
			$client->close();
			$this->assertFalse($client->connected());
		}
	}