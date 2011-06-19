<pre>
<?php
	/**
	 * Example showing pipelining functionality
	 * 
	 * @package Redis\Examples
	 */
	require '..\library\RedisPHPlay.php';
	require '..\library\Benchmark.php';
	
	$benchmark = new Benchmark();
	$benchmark->start('total');
	
	$batch = 5000; // batch command per 100
	$times = 3.5; // send $times * $batch commands

	$benchmark->start('connect');
	$redis = new RedisManager();
	$client = $redis->connect('127.0.0.1', 6379);
	$benchmark->stop('connect');	

	$benchmark->start('normal');
	for($i = 0; $i <= ($times * $batch); $i++)  
		$client->SET('a_' . $i, $i);
	$benchmark->stop('normal');

	$benchmark->start('pipeline');
	$client->pipeline($batch); // start pipelining on $batch nr of commands
	for($i = 0; $i <= ($times * $batch); $i++)  
		$client->SET('a_' . $i, $i);
	$client->flush(); // make sure any leftover commands are sent
	$benchmark->stop('pipeline');

	$benchmark->stop('total');

	$benchmark->display();