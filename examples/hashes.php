<pre>
<?php
	/**
	 * Example showing basic functionality
	 * 
	 * @package Redis\Examples
	 */
	require '..\library\RedisPHPlay.php';

	$redis = new RedisManager();
	$client = $redis->connect('127.0.0.1', 6379);
	$client->auth('test');

	printf("COMMAND arguments (= expected) -> actual\n");
	printf("\n");

	printf("HMSET array('a' => 1, 'b' => 2) (= 0) -> %d\n", $client->HMSET('foo', array('a' => 1, 'b' => 2)));
	printf("\n");
