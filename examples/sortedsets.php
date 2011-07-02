<pre>
<?php
	/**
	 * Example showing basic sorted set functionality
	 * 
	 * @package Redis\Examples
	 */
	require '..\library\RedisPHPlay.php';

	$redis = new RedisManager();
	$client = $redis->connect('127.0.0.1', 6379);
	$client->auth('test');

	printf("COMMAND arguments (= expected) -> actual\n");
	printf("\n");

	$client->DEL('set');
	printf("%s -> %d\n", "ZADD('set', 20, 'a')", $client->ZADD('set', 20, 'a'));
	printf("%s -> %d\n", "ZADD('set',  0, 'b')", $client->ZADD('set',  0, 'b'));
	printf("%s -> %d\n", "ZADD('set', 10, 'c')", $client->ZADD('set', 10, 'c'));
	printf("%s -> %d\n", "ZCARD('set')", $client->ZCARD('set'));
	printf("%s -> \n", "ZRANGE('set', 0, -1)");
	print_r($client->ZRANGE('set', 0, -1));
	printf("\n");