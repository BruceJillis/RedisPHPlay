<pre>
<?php
	/**
	 * Example showing basic functionality
	 * 
	 * @package Redis\Examples
	 */
	require '..\library\PHPRedisFast.php';

	$redis = new RedisManager();
	$client = $redis->connect('127.0.0.1', 6379);
	$client->auth('test');

	printf("COMMAND arguments (= expected) -> actual\n");
	printf("\n");

	printf("SET a 1 (= 1) -> %d\n", $client->SET('a', 1));
	printf("TYPE a (= 'string') -> %s\n", $client->TYPE('a'));
	printf("DEL a b (= 1) -> %d\n", $client->DEL('a', 'b'));
	printf("DEL a b (= 0) -> %d\n", $client->DEL('a', 'b'));
	printf("\n");

	printf("EXISTS a (= 0) -> %d\n", $client->EXISTS('a'));
	printf("SET a 1 (= 1) -> %d\n", $client->SET('a', 1));
	printf("EXISTS a (= 1) -> %d\n", $client->EXISTS('a'));
	printf("DEL a (= 1) -> %d\n", $client->DEL('a'));
	printf("\n");

	printf("SET a 1 (= 1) -> %d\n", $client->SET('a', 1));
	printf("EXPIRE a 2 (= 1) -> %d\n", $client->EXPIRE('a', 2));
	sleep(1);
	printf("1s EXISTS a (= 1) -> %d\n", $client->EXISTS('a'));
	sleep(2);
	printf("3s EXISTS a (= 0) -> %d\n", $client->EXISTS('a'));
	printf("\n");

	printf("SET a 1 (= 1) -> %d\n", $client->SET('a', 1));
	$time = strtotime("+2 seconds");
	printf("EXPIRE a {$time} (= 1) -> %d\n", $client->EXPIREAT('a', $time));
	sleep(1);
	printf("1s EXISTS a (= 1) -> %d\n", $client->EXISTS('a'));
	sleep(2);
	printf("3s EXISTS a (= 0) -> %d\n", $client->EXISTS('a'));
	printf("\n");
	