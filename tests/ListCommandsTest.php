<?php
	require '..\library\RedisPHPlay.php';
	
	/**
	 * Test for the List commands (http://redis.io/commands#list).
	 * 
	 * @package Redis\Tests
	 */
	class ListsCommandsTest extends PHPUnit_Framework_TestCase {
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
		 * Test the LPUSH LLEN LINDEX command
		 */
		function testLPushLLenLIndex() {
			$client = $this->connect();
			$client->DEL('key1');
			$this->assertFalse($client->EXISTS('key1'));
			$client->LPUSH('key1', 'a');
			$client->LPUSH('key1', 'b');
			$client->LPUSH('key1', 'c');
			$this->assertTrue($client->EXISTS('key1'));
			$this->assertEquals(3, $client->LLEN('key1'));
			$this->assertEquals('c', $client->LINDEX('key1', 0));
			$this->assertEquals('b', $client->LINDEX('key1', 1));
			$this->assertEquals('a', $client->LINDEX('key1', 2));
		}

		/**
		 * Test BLPOP 
		 */
		function testBLPop() {
			$client = $this->connect();
			$client->DEL('key1');
			$this->assertFalse($client->EXISTS('key1'));
			$client->LPUSH('key1', 'a');
			$this->assertEquals(1, $client->LLEN('key1'));
			$time = microtime(true);
			$ans = $client->BLPOP('key1', 1);
			$this->assertEquals('a', $ans['key1']);
			$delta = microtime(true) - $time;
			$this->assertTrue($delta < 0.1);
			$time = microtime(true);
			$ans = $client->BLPOP('key1', 1);
			$delta = microtime(true) - $time;
			$this->assertTrue($delta > 0.99);
		}

		/**
		 * Test BRPOP 
		 */
		function testBRPop() {
			$client = $this->connect();
			$client->DEL('key1');
			$this->assertFalse($client->EXISTS('key1'));
			$client->LPUSH('key1', 'a');
			$this->assertEquals(1, $client->LLEN('key1'));
			$time = microtime(true);
			$ans = $client->BRPOP('key1', 1);
			$this->assertEquals('a', $ans['key1']);
			$delta = microtime(true) - $time;
			$this->assertTrue($delta < 0.1);
			$time = microtime(true);
			$ans = $client->BRPOP('key1', 1);
			$delta = microtime(true) - $time;
			$this->assertTrue($delta > 0.99);
		}

		/**
		 * Test the LPOP command
		 */
		function testLPop() {
			$client = $this->connect();
			$client->DEL('key1');
			$this->assertFalse($client->EXISTS('key1'));
			$client->LPUSH('key1', 'a');
			$client->LPUSH('key1', 'b');
			$client->LPUSH('key1', 'c');
			$this->assertTrue($client->EXISTS('key1'));
			$this->assertEquals(3, $client->LLEN('key1'));
			$this->assertEquals('c', $client->LPOP('key1'));
			$this->assertEquals('b', $client->LPOP('key1'));
			$this->assertEquals('a', $client->LPOP('key1'));
			$this->assertEquals(0, $client->LLEN('key1'));
		}

		/**
		 * Test the LRANGE command
		 */
		function testLRANGE() {
			$client = $this->connect();
			$client->DEL('key1');
			$this->assertFalse($client->EXISTS('key1'));
			$client->LPUSH('key1', 'a');
			$client->LPUSH('key1', 'b');
			$client->LPUSH('key1', 'c');
			$this->assertTrue($client->EXISTS('key1'));
			$res = $client->LRANGE('key1', 0, 1);
			$this->assertEquals('c', $res[0]);
			$this->assertEquals('b', $res[1]);
		}


		/**
		 * Test LINSERT
		 */
		function testLInsert() {
			$client = $this->connect();
			$client->DEL('key1');
			$this->assertFalse($client->EXISTS('key1'));
			$client->LPUSH('key1', 'a');
			$client->LPUSH('key1', 'c');
			$this->assertTrue($client->EXISTS('key1'));
			$res = $client->LRANGE('key1', 0, 1);
			$this->assertEquals('c', $res[0]);
			$this->assertEquals('a', $res[1]);
			$client->LINSERT('key1', 'BEFORE', 'a', 'b');
			$res = $client->LRANGE('key1', 0, 1);
			$this->assertEquals('c', $res[0]);
			$this->assertEquals('b', $res[1]);
		}

		/**
		 * Test the LREM command
		 */
		function testLREM() {
			$client = $this->connect();
			$client->DEL('key1');
			$this->assertFalse($client->EXISTS('key1'));
			$client->LPUSH('key1', 'a');
			$client->LPUSH('key1', 'b');
			$client->LPUSH('key1', 'c');
			$client->LREM('key1', 1, 'c');
			$res = $client->LRANGE('key1', 0, 1);
			$this->assertEquals('b', $res[0]);
			$this->assertEquals('a', $res[1]);
		}

		/**
		 * Test the LPUSHX command
		 */
		function testLPushX() {
			$client = $this->connect();
			$client->DEL('key1');
			$this->assertFalse($client->EXISTS('key1'));
			$client->LPUSHX('key1', 'a');
			$this->assertFalse($client->EXISTS('key1'));
			$client->LPUSH('key1', 'a');
			$client->LPUSHX('key1', 'b');
			$this->assertTrue($client->EXISTS('key1'));
			$this->assertEquals(2, $client->LLEN('key1'));
		}

		/**
		 * Test the RPUSHX command
		 */
		function testRPushX() {
			$client = $this->connect();
			$client->DEL('key1');
			$this->assertFalse($client->EXISTS('key1'));
			$client->RPUSHX('key1', 'a');
			$this->assertFalse($client->EXISTS('key1'));
			$client->RPUSH('key1', 'a');
			$client->RPUSHX('key1', 'b');
			$this->assertTrue($client->EXISTS('key1'));
			$this->assertEquals(2, $client->LLEN('key1'));
		}

		/**
		 * Test the RPush, RPop command
		 */
		function testRPushRPop() {
			$client = $this->connect();
			$client->DEL('key1');
			$this->assertFalse($client->EXISTS('key1'));
			$client->RPUSH('key1', 'a');
			$client->RPUSH('key1', 'b');
			$client->RPUSH('key1', 'c');
			$this->assertTrue($client->EXISTS('key1'));
			$this->assertEquals(3, $client->LLEN('key1'));
			$this->assertEquals('c', $client->RPOP('key1'));
			$this->assertEquals('b', $client->RPOP('key1'));
			$this->assertEquals('a', $client->RPOP('key1'));
			$this->assertEquals(0, $client->LLEN('key1'));
		}

		/**
		 * Test the RPOPLPUSH command
		 */
		function testRPopLPush() {
			$client = $this->connect();
			$client->DEL('key1');
			$client->DEL('key2');
			$this->assertFalse($client->EXISTS('key1'));
			$this->assertFalse($client->EXISTS('key2'));
			$client->RPUSH('key1', 'a');
			$client->RPUSH('key2', '1');
			$this->assertTrue($client->EXISTS('key1'));
			$this->assertTrue($client->EXISTS('key2'));
			$client->RPOPLPUSH('key1', 'key2');
			$this->assertEquals(0, $client->LLEN('key1'));
			$this->assertEquals(2, $client->LLEN('key2'));
			$res = $client->LRANGE('key2', 0, -1);
			$this->assertEquals('a', $res[0]);
			$this->assertEquals('1', $res[1]);
		}

		/**
		 * Test the LTRIM command
		 */
		function testLTrim() {
			$client = $this->connect();
			$client->DEL('key1');
			$this->assertFalse($client->EXISTS('key1'));
			$client->LPUSH('key1', 'a');
			$client->LPUSH('key1', 'b');
			$client->LPUSH('key1', 'c');
			$this->assertEquals(3, $client->LLEN('key1'));
			$client->LTRIM('key1', 0, 1);
			$this->assertEquals(2, $client->LLEN('key1'));
		}

		/**
		 * Test the LSET command
		 */
		function testLSet() {
			$client = $this->connect();
			$client->DEL('key1');
			$this->assertFalse($client->EXISTS('key1'));
			$client->LPUSH('key1', 'a');
			$client->LPUSH('key1', 'b');
			$client->LPUSH('key1', 'c');
			$this->assertEquals(3, $client->LLEN('key1'));
			$client->LSET('key1', 1, 'd');
			$this->assertEquals('d', $client->LINDEX('key1', 1));
		}
	}