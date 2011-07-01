<pre>
<?php
	/**
	 * Example showing basic functionality
	 * 
	 * @package Redis\Examples
	 */
	require '..\library\RedisPHPlay.php';
	require '..\library\benchmark\Benchmark.php';

	$redis = new RedisManager();
	$client = $redis->connect('127.0.0.1', 6379);
	$client->auth('test');

	printf("COMMAND arguments (= expected) -> actual\n");
	printf("\n");

	$bm = new Benchmark();

	$client->DEL('list');
	printf("LPUSH list a -> %d\n", $client->LPUSH('list', 'a'));
	printf("LPUSH list b -> %d\n", $client->LPUSH('list', 'b'));
	printf("LRANGE 0 -1 -> \n");
	print_r($client->LRANGE('list', 0, -1));
	printf("LINDEX list 0 -> %s\n", $client->LINDEX('list', 0));
	printf("LINDEX list 1 -> %s\n", $client->LINDEX('list', 1));
	printf("LLEN list -> %d\n", $client->LLEN('list'));
	printf("RPOP list -> %s\n", $client->RPOP('list'));
	printf("BRPOP list ->\n");

	$bm->start('BRPOP that does not block');
	print_r($client->BRPOP('list', 30));
	$bm->stop('BRPOP that does not block');

	$bm->start('BRPOP that does block');
	print_r($client->BRPOP('list', 5));
	$bm->stop('BRPOP that does block');
	printf("LLEN list -> %d\n", $client->LLEN('list'));
	printf("\n");

	$bm->display();