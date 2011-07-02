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

		/**
		 * Testing SDIFFSTORE
		 */		
		public function testSDiffStore() {
			$client = $this->connect();	
			$client->DEL('set1', 'set2', 'set3', 'set4', 'set5', 'set6');
			$this->assertEquals(1, $client->SADD('set1', 'a'));
			$this->assertEquals(1, $client->SADD('set1', 'b'));
			$this->assertEquals(1, $client->SADD('set1', 'c'));
			$this->assertEquals(1, $client->SADD('set2', 'a'));
			$this->assertEquals(1, $client->SADD('set3', 'b'));
			$this->assertEquals(1, $client->SADD('set3', 'c'));
			$diff = $client->SDIFFSTORE('set4', 'set1', 'set2');
			$diff = $client->SDIFFSTORE('set5', 'set1', 'set3');
			$diff = $client->SDIFFSTORE('set6', 'set1', 'set2', 'set3');
			$this->assertEquals(2, $client->SCARD('set4'));
			$this->assertEquals(1, $client->SCARD('set5'));
			$this->assertEquals(0, $client->SCARD('set6'));
		}

		/**
		 * Testing SINTER
		 */		
		public function testSInter() {
			$client = $this->connect();	
			$client->DEL('set1', 'set2', 'set3');
			$this->assertEquals(1, $client->SADD('set1', 'a'));
			$this->assertEquals(1, $client->SADD('set1', 'b'));
			$this->assertEquals(1, $client->SADD('set1', 'c'));
			$this->assertEquals(1, $client->SADD('set2', 'a'));
			$this->assertEquals(1, $client->SADD('set3', 'b'));
			$this->assertEquals(1, $client->SADD('set3', 'c'));
			$diff = $client->SINTER('set1', 'set2');
			$this->assertEquals('a', $diff[0]);
			$diff = $client->SINTER('set1', 'set3');
			$this->assertEquals('c', $diff[0]);
			$this->assertEquals('b', $diff[1]);
		}

		/**
		 * Testing SINTERSTORE
		 */		
		public function testSInterstore() {
			$client = $this->connect();	
			$client->DEL('set1', 'set2', 'set3', 'set4', 'set5', 'set6');
			$this->assertEquals(1, $client->SADD('set1', 'a'));
			$this->assertEquals(1, $client->SADD('set1', 'b'));
			$this->assertEquals(1, $client->SADD('set1', 'c'));
			$this->assertEquals(1, $client->SADD('set2', 'a'));
			$this->assertEquals(1, $client->SADD('set3', 'b'));
			$this->assertEquals(1, $client->SADD('set3', 'c'));
			$diff = $client->SINTERSTORE('set4', 'set1', 'set2');
			$diff = $client->SINTERSTORE('set5', 'set1', 'set3');
			$diff = $client->SINTERSTORE('set6', 'set1', 'set2', 'set3');
			$this->assertEquals(1, $client->SCARD('set4')); // a is in all sets
			$this->assertEquals(2, $client->SCARD('set5')); // b,c are in all sets
			$this->assertEquals(0, $client->SCARD('set6')); // no char is in all sets
		}

		/**
		 * Testing SISMEMBER
		 */		
		public function testSIsMember() {
			$client = $this->connect();	
			$client->DEL('set1');
			$this->assertEquals(1, $client->SADD('set1', 'a'));
			$this->assertEquals(1, $client->SADD('set1', 'b'));
			$this->assertEquals(1, $client->SADD('set1', 'c'));
			$this->assertTrue($client->SISMEMBER('set1', 'a'));
			$this->assertTrue($client->SISMEMBER('set1', 'b'));
			$this->assertTrue($client->SISMEMBER('set1', 'c'));
			$this->assertFalse($client->SISMEMBER('set1', 'd'));
		}

		/**
		 * Testing SMEMBERS
		 */		
		public function testSMembers() {
			$client = $this->connect();	
			$client->DEL('set1');
			$this->assertEquals(1, $client->SADD('set1', 'a'));
			$this->assertEquals(1, $client->SADD('set1', 'b'));
			$this->assertEquals(1, $client->SADD('set1', 'c'));
			$res = $client->SMEMBERS('set1');
			$this->assertEquals('c', $res[0]);
			$this->assertEquals('a', $res[1]);
			$this->assertEquals('b', $res[2]);
		}

		/**
		 * Testing SMOVE
		 */		
		public function testSMove() {
			$client = $this->connect();	
			$client->DEL('set1', 'set2');
			$this->assertEquals(1, $client->SADD('set1', 'a'));
			$this->assertEquals(1, $client->SADD('set1', 'b'));
			$this->assertEquals(1, $client->SADD('set1', 'c'));
			$this->assertEquals(3, $client->SCARD('set1'));
			$client->SMOVE('set1', 'set2', 'a');
			$client->SMOVE('set1', 'set2', 'b');
			$client->SMOVE('set1', 'set2', 'c');
			$res = $client->SMEMBERS('set2');
			$this->assertEquals(0, $client->SCARD('set1'));
			$this->assertEquals(3, $client->SCARD('set2'));
			$this->assertEquals('c', $res[0]);
			$this->assertEquals('a', $res[1]);
			$this->assertEquals('b', $res[2]);
		}

		/**
		 * Testing SPOP
		 */		
		public function testSPop() {
			$client = $this->connect();	
			$client->DEL('set1', 'set2');
			$this->assertEquals(1, $client->SADD('set1', 'a'));
			$this->assertEquals(1, $client->SADD('set1', 'b'));
			$this->assertEquals(1, $client->SADD('set1', 'c'));
			$this->assertEquals(3, $client->SCARD('set1'));
			$arr = array('a', 'b', 'c');
			$this->assertTrue(in_array($client->SPOP('set1'), $arr));
			$this->assertTrue(in_array($client->SPOP('set1'), $arr));
			$this->assertTrue(in_array($client->SPOP('set1'), $arr));
			$this->assertEquals(0, $client->SCARD('set1'));
		}

		/**
		 * Testing SRANDMEMBER
		 */		
		public function testSRandMember() {
			$client = $this->connect();	
			$client->DEL('set1', 'set2');
			$this->assertEquals(1, $client->SADD('set1', 'a'));
			$this->assertEquals(1, $client->SADD('set1', 'b'));
			$this->assertEquals(1, $client->SADD('set1', 'c'));
			$this->assertEquals(3, $client->SCARD('set1'));
			$arr = array('a', 'b', 'c');
			$this->assertTrue(in_array($client->SRANDMEMBER('set1'), $arr));
			$this->assertTrue(in_array($client->SRANDMEMBER('set1'), $arr));
			$this->assertTrue(in_array($client->SRANDMEMBER('set1'), $arr));
			$this->assertEquals(3, $client->SCARD('set1'));
		}

		/**
		 * Testing SREM
		 */		
		public function testSRem() {
			$client = $this->connect();	
			$client->DEL('set1', 'set2');
			$this->assertEquals(1, $client->SADD('set1', 'a'));
			$this->assertEquals(1, $client->SADD('set1', 'b'));
			$this->assertEquals(1, $client->SADD('set1', 'c'));
			$this->assertEquals(3, $client->SCARD('set1'));
			$client->SREM('set1', 'a');
			$arr = array('b', 'c');
			$this->assertTrue(in_array($client->SPOP('set1'), $arr));
			$this->assertTrue(in_array($client->SPOP('set1'), $arr));
			$this->assertEquals(0, $client->SCARD('set1'));
		}

		/**
		 * Testing SUNION
		 */		
		public function testSUnion() {
			$client = $this->connect();	
			$client->DEL('set1', 'set2', 'set3');
			$this->assertEquals(1, $client->SADD('set1', 'a'));
			$this->assertEquals(1, $client->SADD('set1', 'b'));
			$this->assertEquals(1, $client->SADD('set1', 'c'));

			$this->assertEquals(1, $client->SADD('set2', 'd'));

			$this->assertEquals(1, $client->SADD('set3', 'e'));
			$this->assertEquals(1, $client->SADD('set3', 'f'));
			$diff = $client->SUNION('set1', 'set2');
			$chars = array('a', 'b', 'c', 'd');
			foreach($diff as $char)
				$this->assertTrue(in_array($char, $chars));
			$diff = $client->SUNION('set1', 'set3');
			$chars = array('a', 'b', 'c', 'e', 'f');
			foreach($diff as $char)
				$this->assertTrue(in_array($char, $chars));
		}

		/**
		 * Testing SUNIONSTORE
		 */		
		public function testSUnionStore() {
			$client = $this->connect();	
			$client->DEL('set1', 'set2', 'set3', 'set4', 'set5', 'set6');
			$this->assertEquals(1, $client->SADD('set1', 'a'));
			$this->assertEquals(1, $client->SADD('set1', 'b'));
			$this->assertEquals(1, $client->SADD('set1', 'c'));

			$this->assertEquals(1, $client->SADD('set2', 'a'));

			$this->assertEquals(1, $client->SADD('set3', 'e'));
			$this->assertEquals(1, $client->SADD('set3', 'c'));
			$diff = $client->SUNIONSTORE('set4', 'set1', 'set2');
			$diff = $client->SUNIONSTORE('set5', 'set1', 'set3');
			$diff = $client->SUNIONSTORE('set6', 'set1', 'set2', 'set3');
			$this->assertEquals(3, $client->SCARD('set4')); 
			$this->assertEquals(4, $client->SCARD('set5')); 
			$this->assertEquals(4, $client->SCARD('set6')); 
		}
	}