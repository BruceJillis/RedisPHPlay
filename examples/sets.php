<pre>
<?php
	/**
	 * Example showing basic set functionality
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
	printf("%s -> %d\n", "SADD('set', 1)", $client->SADD('set', 1));
	printf("%s -> %d\n", "SADD('set', 2)", $client->SADD('set', 2));
	printf("%s -> %d\n", "SADD('set', 3)", $client->SADD('set', 3));

	printf("%s -> \n", "SMEMBERS('set')");
	print_r($client->SMEMBERS('set'));

	printf("%s -> %s\n", "SISMEMBER('set', 2)", $client->SISMEMBER('set', 2));

