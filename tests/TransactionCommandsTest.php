<?php
	require '..\library\RedisPHPlay.php';
	
	/**
	 * Test for the Transaction commands ( http://redis.io/commands#transactions ).
	 * 
	 * @package Redis\Tests
	 */
	class TransactionCommandsTest extends PHPUnit_Framework_TestCase {
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

		/**
		 * Testing MULTI, EXECT
		 */		
		public function testMultiExec() {
			$client = $this->connect();
			$client->DEL('key1');
			$client->MULTI();
			$client->LPUSH('key1', 'a');
			$client->LPUSH('key1', 'b');
			$client->LPUSH('key1', 'c');
			$this->assertFalse($client->LLEN('key1'));
			$client->EXEC(); 
			$this->assertEquals(3, $client->LLEN('key1'));
		}

		/**
		 * Testing MULTI, DISCARD
		 */		
		public function testMultiDiscard() {
			$client = $this->connect();
			$client->DEL('key1');
			$client->MULTI();
			$client->LPUSH('key1', 'a');
			$client->LPUSH('key1', 'b');
			$client->LPUSH('key1', 'c');
			$this->assertFalse($client->LLEN('key1'));
			$client->DISCARD(); 
			$this->assertEquals(0, $client->LLEN('key1'));
		}

		/**
		 * Testing WATCH ok
		 */		
		public function testWatchOk() {
			$client = $this->connect();
			$client->DEL('key1', 'key2');
			$client->INCR('key2');
			$client->WATCH('key2'); 
			$client->MULTI();
			$client->LPUSH('key1', 'a');
			$this->assertFalse($client->LLEN('key1'));
			$client->EXEC(); 
			$this->assertEquals(1, $client->LLEN('key1'));
			$client->UNWATCH(); 
		}

		/**
		 * Testing WATCH abort
		 */		
		public function testWatchAbort() {
			$client = $this->connect();
			$client->DEL('key1', 'key2');
			$client->INCR('key2');
			$client->WATCH('key2'); 
			$client->INCR('key2'); // modify
			$client->MULTI();
			$client->LPUSH('key1', 'a');
			$this->assertFalse($client->LLEN('key1'));
			$client->EXEC(); 
			$this->assertEquals(0, $client->LLEN('key1'));
			$client->UNWATCH(); 
		}

	}