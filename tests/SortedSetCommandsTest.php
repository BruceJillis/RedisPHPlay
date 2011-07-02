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

		/**
		 * Testing key command ZADD, ZCARD
		 */		
		public function testZCardZAdd() {
			$client = $this->connect();	
			$client->DEL('set');
			$client->ZADD('set', 1, 'a');
			$client->ZADD('set', 2, 'b');
			$client->ZADD('set', 3, 'c');
			$this->assertEquals(3, $client->ZCARD('set'));
		}

		/**
		 * Testing key command ZCOUNT
		 */		
		public function testZCount() {
			$client = $this->connect();	
			$client->DEL('set');
			$client->ZADD('set', 1, 'a');
			$client->ZADD('set', 2, 'b');
			$client->ZADD('set', 3, 'c');
			$this->assertEquals(2, $client->ZCOUNT('set', 2, 3));
		}

		/**
		 * Testing key command ZRANGE
		 */		
		public function testZRange() {
			$client = $this->connect();	
			$client->DEL('set');
			$client->ZADD('set', 1, 'a');
			$client->ZADD('set', 2, 'b');
			$client->ZADD('set', 3, 'c');
			$res = $client->ZRANGE('set', 0, -1);
			$this->assertEquals('a', $res[0]);
			$this->assertEquals('b', $res[1]);
			$this->assertEquals('c', $res[2]);
		}

		/**
		 * Testing key command ZINCRBY
		 */		
		public function testZIncrBy() {
			$client = $this->connect();	
			$client->DEL('set');
			$client->ZADD('set', 1, 'a');
			$client->ZADD('set', 2, 'b');
			$client->ZADD('set', 3, 'c');
			$res = $client->ZRANGE('set', 0, -1);
			$this->assertEquals('a', $res[0]);
			$this->assertEquals('b', $res[1]);
			$this->assertEquals('c', $res[2]);
			$client->ZINCRBY('set', 3, 'a');
			$res = $client->ZRANGE('set', 0, -1);
			$this->assertEquals('b', $res[0]);
			$this->assertEquals('c', $res[1]);
			$this->assertEquals('a', $res[2]);
		}

		/**
		 * Testing key command ZRANGEBYSCORE
		 */		
		public function testZRangeByScore() {
			$client = $this->connect();	
			$client->DEL('set');
			$client->ZADD('set', 1, 'a');
			$client->ZADD('set', 2, 'b');
			$client->ZADD('set', 3, 'c');
			$res = $client->ZRANGEBYSCORE('set', 2, 3);
			$this->assertEquals('b', $res[0]);
			$this->assertEquals('c', $res[1]);
		}

		/**
		 * Testing key command ZRANK
		 */		
		public function testZRank() {
			$client = $this->connect();	
			$client->DEL('set');
			$client->ZADD('set', 1, 'a');
			$client->ZADD('set', 2, 'b');
			$client->ZADD('set', 3, 'c');
			$this->assertEquals(0, $client->ZRANK('set', 'a'));
			$this->assertEquals(2, $client->ZRANK('set', 'c'));
		}

		/**
		 * Testing key command ZREM
		 */		
		public function testZRem() {
			$client = $this->connect();	
			$client->DEL('set');
			$client->ZADD('set', 1, 'a');
			$client->ZADD('set', 2, 'b');
			$client->ZADD('set', 3, 'c');
			$client->ZREM('set', 'a');
			$client->ZREM('set', 'b');
			$client->ZREM('set', 'c');
			$this->assertEquals(0, $client->ZCARD('set'));
		}
	}