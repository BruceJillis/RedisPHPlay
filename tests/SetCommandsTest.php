<?php
	require '..\library\RedisPHPlay.php';
	
	/**
	 * Test for the Key commands (http://redis.io/commands#generic).
	 * 
	 * @package Redis\Tests
	 */
	class SetCommandsTest extends PHPUnit_Framework_TestCase {
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
		 * Testing SADD
		 */		
		public function testSAdd() {
			$client = $this->connect();	
			$client->DEL('set1');
			$this->assertEquals(1, $client->SADD('set1', 'a'));
			$this->assertEquals(1, $client->SADD('set1', 'b'));
			$this->assertEquals(1, $client->SADD('set1', 'c'));
		}

		/**
		 * Testing SCARD
		 */		
		public function testSCard() {
			$client = $this->connect();	
			$client->DEL('set1');
			$this->assertEquals(1, $client->SADD('set1', 'a'));
			$this->assertEquals(1, $client->SADD('set1', 'b'));
			$this->assertEquals(1, $client->SADD('set1', 'c'));
			$this->assertEquals(3, $client->SCARD('set1'));
			$this->assertEquals(0, $client->SADD('set1', 'a'));
			$this->assertEquals(3, $client->SCARD('set1'));
		}

		/**
		 * Testing SDIFF
		 */		
		public function testSDiff() {
			$client = $this->connect();	
			$client->DEL('set1', 'set2', 'set3');
			$this->assertEquals(1, $client->SADD('set1', 'a'));
			$this->assertEquals(1, $client->SADD('set1', 'b'));
			$this->assertEquals(1, $client->SADD('set1', 'c'));
			$this->assertEquals(1, $client->SADD('set2', 'a'));
			$this->assertEquals(1, $client->SADD('set3', 'b'));
			$this->assertEquals(1, $client->SADD('set3', 'c'));
			$diff = $client->SDIFF('set1', 'set2');
			$this->assertEquals('c', $diff[0]);
			$this->assertEquals('b', $diff[1]);
			$diff = $client->SDIFF('set1', 'set3');
			$this->assertEquals('a', $diff[0]);
		}
	}