<?php
	require '..\library\PHPRedisFast.php';
	
	/**
	 * Test for the Key commands (http://redis.io/commands#generic).
	 * 
	 * @package Redis\Tests
	 */
	class KeyCommandsTest extends PHPUnit_Framework_TestCase {
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
		 * Testing key command GET
		 */		
		public function testGet() {
			$client = $this->connect();	
			$this->assertEquals($client->GET('nonexistant'), null);
			$client->close();
			$this->assertFalse($client->connected());
		}

		/**
		 * Testing key commands SET, DEL and EXISTS
		 */		
		public function testSetDelExists() {
			$client = $this->connect();
			$this->assertTrue($client->SET('a', 10));
			$this->assertTrue($client->EXISTS('a'));
			$this->assertEquals($client->DEL('a'), 1);
			$this->assertTrue($client->SET('a', 100));
			$this->assertTrue($client->SET('b', 200));
			$this->assertEquals($client->DEL('a', 'b'), 2);
			$client->close();
			$this->assertFalse($client->connected());
		}

		/**
		 * Testing key commands SET, EXISTS, EXPIRE
		 */		
		public function testSetExistsExpire() {
			$client = $this->connect();
			$this->assertEquals($client->SET('a', 10), true);
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
		 * Testing key commands SET, EXISTS, EXPIREAT
		 */		
		public function testSetExistsExpireAt() {
			$client = $this->connect();
			$this->assertEquals($client->SET('a', 10), true);
			$this->assertTrue($client->EXISTS('a'));
			$time = strtotime("+2 seconds");
			$this->assertTrue($client->EXPIREAT('a', $time));
			$this->assertTrue($client->EXISTS('a'));
			sleep(1);
			$this->assertTrue($client->EXISTS('a'));
			sleep(2);
			$this->assertFalse($client->EXISTS('a'));
			$client->close();
			$this->assertFalse($client->connected());
		}

		/**
		 * Testing key command RANDOMKEY
		 */		
		public function testRandomKey() {
			$client = $this->connect();	
			$this->assertEquals($client->SET('a', 10), true);
			$this->assertEquals('a', $client->RANDOMKEY());
			$client->close();
			$this->assertFalse($client->connected());
		}

		/**
		 * Testing key commands SET, EXISTS, EXPIRE, PERSIST
		 */		
		public function testSetExistsExpirePersist() {
			$client = $this->connect();
			$this->assertEquals($client->SET('a', 10), true);
			$this->assertTrue($client->EXISTS('a'));
			$client->EXPIRE('a', 2);
			$this->assertTrue($client->EXISTS('a'));
			sleep(1);
			$this->assertTrue($client->EXISTS('a'));
			$client->PERSIST('a');
			sleep(2);
			$this->assertTrue($client->EXISTS('a'));
			$client->close();
			$this->assertFalse($client->connected());
		}

		/**
		 * Testing key command KEYS
		 */		
		public function testKeys() {
			$client = $this->connect();
			$this->assertEquals($client->SET('a', 10), true);
			$this->assertEquals($client->SET('b', 11), true);
			$this->assertEquals($client->SET('c', 12), true);
			$keys = $client->KEYS('?');
			$this->assertTrue(is_array($keys));
			$this->assertEquals(3, count($keys));
			$this->assertEquals('a', $keys[0]);
			$this->assertEquals('b', $keys[1]);
			$this->assertEquals('c', $keys[2]);
			$this->assertEquals($client->DEL('a', 'b', 'c'), 3);
			$client->close();
			$this->assertFalse($client->connected());
		}

		/**
		 * Testing key command RENAME
		 */		
		public function testRename() {
			$client = $this->connect();
			$this->assertEquals($client->SET('a', 10), true);
			$this->assertEquals(10, $client->GET('a'));
			$client->RENAME('a', 'b');
			$this->assertEquals(10, $client->GET('b'));
			$client->close();
			$this->assertFalse($client->connected());
		}

		/**
		 * Testing key command RENAMENX
		 */		
		public function testRenameNx() {
			$client = $this->connect();
			$this->assertEquals($client->SET('a', 10), true);
			$this->assertEquals(10, $client->GET('a'));
			$client->DEL('b'); // to be sure
			$client->RENAMENX('a', 'b');
			$this->assertEquals(10, $client->GET('b'));
			$this->assertEquals($client->SET('a', 20), true);
			$this->assertEquals(20, $client->GET('a'));
			$client->RENAMENX('a', 'b'); // has no effect so b will remain 10
			$this->assertEquals(10, $client->GET('b'));
			$client->close();
			$this->assertFalse($client->connected());
		}

		/**
		 * Testing key command TTL
		 */		
		public function testTtl() {
			$client = $this->connect();
			$this->assertEquals($client->SET('a', 10), true);
			$client->EXPIRE('a', 2);
			$this->assertEquals(2, $client->TTL('a'));
			sleep(3);
			$this->assertFalse($client->EXISTS('a'));
			$client->close();
			$this->assertFalse($client->connected());
		}

		/**
		 * Testing key command TYPE
		 */		
		public function testType() {
			$client = $this->connect();
			$this->assertEquals($client->SET('a', 10), true);
			$this->assertEquals('string', $client->TYPE('a'));
			$this->assertEquals(1, $client->DEL('a'));
			$client->close();
			$this->assertFalse($client->connected());
		}

		/**
		 * Testing key command MOVE
		 */		
		public function testMove() {
			$client = $this->connect();
			$client->SELECT(0);
			$this->assertEquals($client->SET('a', 10), true);
			$this->assertEquals(10, $client->GET('a'));
			$client->SELECT(1);
			$this->assertEquals(null, $client->GET('a'));
			$client->SELECT(0);
			$client->MOVE('a', 1);
			$this->assertEquals(null, $client->GET('a'));
			$client->SELECT(1);
			$this->assertEquals(10, $client->GET('a'));
			$client->DEL('a');
			$client->SELECT(0);
			$client->close();
			$this->assertFalse($client->connected());
		}
	}