<pre>
<?php
	/**
	 * Currently inactive example showing the proposed partitioning interface
	 * 
	 * @package Redis\Examples
	 */
	require '..\library\RedisPHPlay.php';

	$redis = new RedisManager();
	$cluster = $redis->cluster();
	$cluster->connect('127.0.0.1', 6380, 'test');

	die();
	$client2 = $redis->connect('127.0.0.1', 6380);
	$client2->auth('test');
	$client3 = $redis->connect('127.0.0.1', 6381);
	$client3->auth('test');
	
	abstract class Distributor {
		abstract function add(RedisClient $client);
		abstract function remove(RedisClient $client);
		abstract function distribute($key);
	}

	class ModuloDistributor extends Distributor {
		private $count = 0;

		function add(RedisClient $client) {
			$this->count += 1;
		}

		function remove(RedisClient $client) {
			$this->count -= 1;
		}

		function distribute($key) {
			$crc = md5($key);
			return $crc % $this->count;
		}
	}

	class HashRingDistributor extends Distributor {
		private $count = 0;
		private $ring  = array();

		function add(RedisClient $client) {
			$this->count += 1;
		}

		function remove(RedisClient $client) {
			$this->count -= 1;
		}

		function distribute($key) {
			$crc = md5($key);
			return $crc % $this->count;
		}
	}

	$range = $redis->partitioning();
	$range->distribution(new ModuloDistributor());
	$range->add($client1, $client2, $client3);
	
	// set and shard and retrieve
	$range->SET('vote:blogpost:1', 1);
	echo $range->GET('vote:blogpost:1');