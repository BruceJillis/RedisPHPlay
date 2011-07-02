<pre>
<?php
	/**
	 * Example showing basic transaction functionality
	 * 
	 * @package Redis\Examples
	 */
	require '..\library\RedisPHPlay.php';

	$redis = new RedisManager();
	$client = $redis->connect('127.0.0.1', 6379);
	$client->auth('test');

	printf("COMMAND arguments (= expected) -> actual\n");
	printf("\n");

	$client->DEL('key1', 'key2');
	
	echo "Execute a succesfull transaction.\n";
	$client->MULTI(); // start transaction block
	$client->LPUSH('key1', 'a');
	$client->LPUSH('key1', 'b');
	$client->LPUSH('key1', 'c');
	printf("%s -> %d\n", "LLEN('key1')", $client->LLEN('key1'));
	$client->EXEC(); // execute transaction block
	printf("%s -> %d\n", "LLEN('key1')", $client->LLEN('key1'));
	printf("\n");

	echo "Discard a successfull transaction.\n";
	$client->MULTI(); // start transaction block
	$client->LPUSH('key2', 'a');
	$client->LPUSH('key2', 'b');
	$client->LPUSH('key2', 'c');
	printf("%s -> %d\n", "LLEN('key2')", $client->LLEN('key2'));
	$client->DISCARD(); // discard transaction block
	printf("%s -> %d\n", "LLEN('key2')", $client->LLEN('key2'));
	printf("\n");

	echo "Watch key, and modify it, creating an unsuccesful transaction.\n";
	$client->WATCH('key1'); 
	$client->LPUSH('key1', '1'); // immediatly modify watched key
	$client->MULTI(); // start transaction block
	$client->LPUSH('key2', '1');
	$client->LPUSH('key2', '2');
	$client->LPUSH('key2', '3');
	printf("%s -> %d\n", "LLEN('key2')", $client->LLEN('key2'));
	$client->EXEC(); // exec transaction block, should not work
	printf("%s -> %d\n", "LLEN('key2')", $client->LLEN('key2'));
	$client->UNWATCH(); 
	printf("\n");