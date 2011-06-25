<?php
	require '..\library\RedisPHPlay.php';
	
	/**
	 * Test for the Hashes commands (http://redis.io/commands#hash).
	 * 
	 * @package Redis\Tests
	 */
	class HashesCommandsTest extends PHPUnit_Framework_TestCase {
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
		 * Test the HSET command
		 */
		function testHSET() {
			$client = $this->connect();
			$client->DEL('key1');
			$this->assertFalse($client->EXISTS('key1'));
			$client->HSET('key1', 'a', 1);
			$this->assertTrue($client->EXISTS('key1'));
			$this->assertEquals('1', $client->HGET('key1', 'a'));
		}

		/**
		 * Test the HEXISTS
		 */
		function testHExists() {
			$client = $this->connect();
			$client->DEL('key1');
			$this->assertFalse($client->EXISTS('key1'));
			$client->HSET('key1', 'a', 1);
			$this->assertTrue($client->EXISTS('key1'));
			$this->assertTrue($client->HEXISTS('key1', 'a'));
			$this->assertFalse($client->HEXISTS('key1', 'b'));
		}

		/**
		 * Test the HGET
		 */
		function testHGet() {
			$client = $this->connect();
			$client->DEL('key1');
			$this->assertFalse($client->EXISTS('key1'));
			$client->HSET('key1', 'a', 'zxy');
			$this->assertEquals('zxy', $client->HGET('key1', 'a'));
		}

		/**
		 * Test the HGETALL
		 */
		function testHGetAll() {
			$client = $this->connect();
			$client->DEL('key1');
			$this->assertFalse($client->EXISTS('key1'));
			$client->HSET('key1', 'a', 'zxy');
			$client->HSET('key1', 'b', 'abc');
			$ans = $client->HGETALL('key1');
			$this->assertTrue(isset($ans['a']));
			$this->assertEquals('zxy', $ans['a']);
			$this->assertTrue(isset($ans['b']));
			$this->assertEquals('abc', $ans['b']);			
		}

		/**
		 * Test the HINCRBY command
		 */
		function testHIncrBy() {
			$client = $this->connect();
			$client->DEL('key1');
			$this->assertFalse($client->EXISTS('key1'));
			$client->HSET('key1', 'a', 1);
			$this->assertTrue($client->EXISTS('key1'));
			$this->assertEquals('1', $client->HGET('key1', 'a'));
			$client->HINCRBY('key1', 'a', 10);
			$this->assertEquals('11', $client->HGET('key1', 'a'));
			$client->HINCRBY('key1', 'a', -20);
			$this->assertEquals('-9', $client->HGET('key1', 'a'));
		}

		/**
		 * Test the HKEYS and the HVALS command
		 */
		function testHKeysHVals() {
			$client = $this->connect();
			$client->DEL('key1');
			$this->assertFalse($client->EXISTS('key1'));
			$client->HSET('key1', 'a', 1);
			$client->HSET('key1', 'b', 2);
			$client->HSET('key1', 'c', 3);
			$this->assertTrue($client->EXISTS('key1'));
			$ans = $client->HKEYS('key1');
			$this->assertEquals(3, count($ans));
			$this->assertEquals('a', $ans[0]);
			$this->assertEquals('b', $ans[1]);
			$this->assertEquals('c', $ans[2]);
			$ans = $client->HVALS('key1');
			$this->assertEquals(3, count($ans));
			$this->assertEquals('1', $ans[0]);
			$this->assertEquals('2', $ans[1]);
			$this->assertEquals('3', $ans[2]);
		}

		/**
		 * Test the HDEL
		 */
		function testHDel() {
			$client = $this->connect();
			$client->DEL('key1');
			$this->assertFalse($client->EXISTS('key1'));
			$client->HSET('key1', 'a', 1);
			$client->HSET('key1', 'b', 2);
			$client->HSET('key1', 'c', 3);
			$this->assertTrue($client->EXISTS('key1'));
			$ans = $client->HKEYS('key1');
			$this->assertEquals(3, count($ans));
			$this->assertEquals('a', $ans[0]);
			$this->assertEquals('b', $ans[1]);
			$this->assertEquals('c', $ans[2]);
			$client->HDEL('key1', 'b');
			$ans = $client->HKEYS('key1');
			$this->assertEquals(2, count($ans));
			$this->assertEquals('a', $ans[0]);
			$this->assertEquals('c', $ans[1]);
		}

		/**
		 * Test the HLEN
		 */
		function testHLen() {
			$client = $this->connect();
			$client->DEL('key1');
			$this->assertFalse($client->EXISTS('key1'));
			$client->HSET('key1', 'a', 1);
			$client->HSET('key1', 'b', 2);
			$client->HSET('key1', 'c', 3);
			$this->assertTrue($client->EXISTS('key1'));
			$this->assertEquals(3, $client->HLEN('key1'));
		}

		/**
		 * Test the HMSET/HMGET
		 */
		function testHMSetHMGet() {
			$client = $this->connect();
			$client->DEL('key1');
			$this->assertFalse($client->EXISTS('key1'));
			$client->HMSET('key1', 'a', 10, 'b', 20, 'c', 30, 'd', 40);
			$this->assertTrue($client->EXISTS('key1'));
			$ans = $client->HKEYS('key1');
			$this->assertEquals(4, count($ans));
			$this->assertEquals('a', $ans[0]);
			$this->assertEquals('b', $ans[1]);
			$this->assertEquals('c', $ans[2]);
			$this->assertEquals('d', $ans[3]);
			$ans = $client->HVALS('key1');
			$this->assertEquals(4, count($ans));
			$this->assertEquals('10', $ans[0]);
			$this->assertEquals('20', $ans[1]);
			$this->assertEquals('30', $ans[2]);
			$this->assertEquals('40', $ans[3]);
			$ans = $client->HMGET('key1', 'a', 'b', 'c', 'd');
			$this->assertEquals(4, count($ans));
			$this->assertEquals('10', $ans[0]);
			$this->assertEquals('20', $ans[1]);
			$this->assertEquals('30', $ans[2]);
			$this->assertEquals('40', $ans[3]);
		}

		/**
		 * Test the HSETNX command
		 */
		function testHSetNx() {
			$client = $this->connect();
			$client->DEL('key1');
			$this->assertFalse($client->EXISTS('key1'));
			$client->HSETNX('key1', 'a', 1);
			$this->assertTrue($client->EXISTS('key1'));
			$this->assertEquals('1', $client->HGET('key1', 'a'));
			$client->HSETNX('key1', 'a', 'aaa');
			$this->assertTrue($client->EXISTS('key1'));
			$this->assertEquals('1', $client->HGET('key1', 'a'));
		}
	}