<?php
	require '..\library\RedisPHPlay.php';
	
	/**
	 * Test for the String commands ( http://redis.io/commands#string ).
	 * 
	 * @package Redis\Tests
	 */
	class StringCommandsTest extends PHPUnit_Framework_TestCase {
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
		 * Testing key command APPEND
		 */		
		public function testAppend() {
			$client = $this->connect();	
			$this->assertTrue($client->SET('foo', 'a'));
			$this->assertEquals($client->APPEND('foo', 'z'), 2);
			$this->assertEquals($client->GET('foo'), 'az');
			$this->assertEquals($client->APPEND('foo', 'm'), 3);
			$this->assertEquals($client->GET('foo'), 'azm');
			$client->close();
			$this->assertFalse($client->connected());
		}

		/**
		 * Testing key command DECR
		 */		
		public function testDecr() {
			$client = $this->connect();	
			$this->assertTrue($client->SET('foo', 1));
			$this->assertEquals($client->DECR('foo'), 0);
			$this->assertEquals($client->GET('foo'), 0);
			$client->close();
			$this->assertFalse($client->connected());
		}

		/**
		 * Testing key command DECRBY
		 */		
		public function testDecrBy() {
			$client = $this->connect();	
			$this->assertTrue($client->SET('foo', 1));
			$this->assertEquals($client->DECRBY('foo', 10), -9);
			$this->assertEquals($client->GET('foo'), -9);
			$client->close();
			$this->assertFalse($client->connected());
		}

		/**
		 * Testing key command GET
		 */		
		public function testGet() {
			$client = $this->connect();	
			$this->assertTrue($client->SET('foo', 1));
			$this->assertEquals($client->GET('foo'), 1);
			$this->assertTrue($client->SET('foo', 'a'));
			$this->assertEquals($client->GET('foo'), 'a');
			$this->assertTrue($client->SET('foo', 0.5));
			$this->assertEquals($client->GET('foo'), '0.5');
			$client->close();
			$this->assertFalse($client->connected());
		}

		/**
		 * Testing key command GETSET
		 */		
		public function testGetSet() {
			$client = $this->connect();	
			$this->assertTrue($client->SET('foo', 666));
			$this->assertEquals($client->GETSET('foo', 100), 666);
			$this->assertTrue($client->SET('foo', 'a'));
			$this->assertEquals($client->GET('foo'), 'a');
			$this->assertEquals($client->GETSET('foo', 100), 'a');
			$this->assertEquals($client->GET('foo'), 100);
			$client->close();
			$this->assertFalse($client->connected());
		}
		
		/*
		// Testing key command GETBIT
		public function testGetBit() {
			$client = $this->connect();	
			$this->assertTrue($client->SET('foo', 2*2*2*2)); // 2^4
			$this->assertEquals($client->GET('foo'), 16);
			$this->assertEquals($client->GETBIT('foo', 0), 0);
			$this->assertEquals($client->GETBIT('foo', 1), 1);
			$this->assertEquals($client->GETBIT('foo', 2), 1);
			$client->close();
			$this->assertFalse($client->connected());
		}

		// Testing key command GETRANGE
		public function testGetRange() {
			$client = $this->connect();	
			$this->assertTrue($client->SET('foo', '2*2*2*2')); 
			$this->assertEquals($client->GETRANGE('foo', 1, 5), '*2*2*');
			$client->close();
			$this->assertFalse($client->connected());
		}
		*/
	}