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

	printf("HMSET a = 1, b = 2 (= 0) -> %d\n", $client->HMSET('foo', 'a', 1, 'b', 2));
	printf("HGETALL foo (= array(a=1, b=2)) -> %s\n", print_r($client->HGETALL('foo'), 1));
	printf("\n");
