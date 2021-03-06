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

		/**
		 * Testing key command INCR
		 */		
		public function testIncr() {
			$client = $this->connect();	
			$this->assertTrue($client->SET('foo', 1));
			$this->assertEquals($client->INCR('foo'), 2);
			$this->assertEquals($client->GET('foo'), 2);
			$client->close();
			$this->assertFalse($client->connected());
		}

		/**
		 * Testing key command INCRBY
		 */		
		public function testIncrBy() {
			$client = $this->connect();	
			$this->assertTrue($client->SET('foo', 1));
			$this->assertEquals($client->INCRBY('foo', 10), 11);
			$this->assertEquals($client->GET('foo'), 11);
			$client->close();
			$this->assertFalse($client->connected());
		}

		/**
		 * test MGET
		 */
		function testMGet() {
			$client = $this->connect();	
			$client->set('a', 1);
			$client->set('b', 2);
			$ar = $client->mget('a', 'b');
			$this->assertEquals(2, count($ar));
			$this->assertEquals(1, $ar[0]);
			$this->assertEquals(2, $ar[1]);
			$client->incr('a');
			$client->incr('b');
			$ar = $client->mget('a', 'b');
			$this->assertEquals(2, count($ar));
			$this->assertEquals(2, $ar[0]);
			$this->assertEquals(3, $ar[1]);
		}

		/**
		 * test MSET
		 */
		function testMSet() {
			$client = $this->connect();
			$client->mset('a', 123, 'b', 999);
			$ar = $client->mget('a', 'b');
			$this->assertEquals(2, count($ar));
			$this->assertEquals(123, $ar[0]);
			$this->assertEquals(999, $ar[1]);
			$client->incr('a');
			$client->set('b', 111);
			$ar = $client->mget('a', 'b');
			$this->assertEquals(2, count($ar));
			$this->assertEquals(124, $ar[0]);
			$this->assertEquals(111, $ar[1]);
		}

		/**
		 * test MSETNX
		 */
		function testMSetNX() {
			$client = $this->connect();
			$client->del('a', 'b');
			$client->MSETNX('a', 1, 'b', 2);
			$client->MSETNX('a', 'z', 'b', 'x');
			$this->assertEquals('1', $client->GET('a'));
			$this->assertEquals('2', $client->GET('b'));
		}

		/**
		 * test STRLEN
		 */
		function testStrlen() {
			$client = $this->connect();
			$client->set('foo', 'abcd');
			$this->assertEquals(4, $client->strlen('foo'));
			$client->append('foo', '12');
			$this->assertEquals(6, $client->strlen('foo'));
		}

		/**
		 * Testing key command SETEX
		 */		
		public function testSetEx() {
			$client = $this->connect();
			$client->DEL('a');
			$this->assertFalse($client->EXISTS('a'));
			$this->assertTrue($client->SETEX('a', 10, 3));
			$this->assertTrue($client->EXISTS('a'));
			$client->EXPIRE('a', 2);
			$this->assertTrue($client->EXISTS('a'));
			sleep(1);
			$this->assertTrue($client->EXISTS('a'));
			sleep(2);
			$this->assertFalse($client->EXISTS('a'));
			$client->close();
			$this->assertFalse($client->connected());
		}

		/**
		 * Testing key command SETNX
		 */		
		public function testSetNx() {
			$client = $this->connect();
			$client->DEL('a');
			$this->assertFalse($client->EXISTS('a'));
			$this->assertTrue($client->SETNX('a', 'hello'));
			$this->assertTrue($client->EXISTS('a'));
			$this->assertFalse($client->SETNX('a', 'world'));
			$this->assertEquals('hello', $client->GET('a'));
			$client->close();
		}
	}