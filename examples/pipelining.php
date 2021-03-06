<pre>
<?php
	/**
	 * Example showing pipelining functionality
	 * 
	 * @package Redis\Examples
	 */
	require '..\library\RedisPHPlay.php';
	require '..\library\benchmark\Benchmark.php';
	
	$benchmark = new Benchmark();
	
	$batch = 1000; // batch command per 100
	$times = 2; // send $times * $batch commands
	
	$redis = new RedisManager();
	$client = $redis->connect('127.0.0.1', 6379);
	$client->auth('test');
	
	for($j = 0; $j < 5; $j++ ) {
		$benchmark->start('normal');
		for($i = 0; $i <= ($times * $batch); $i++)  
			$client->SET('a_' . $i, $i);
		$benchmark->stop('normal');

		$benchmark->start('pipeline');
		$pipeline = $client->pipeline($batch); // start pipelining on $batch nr of commands
		for($i = 0; $i <= ($times * $batch); $i++)  
			$pipeline->SET('a_' . $i, $i);
		$pipeline->flush(); // make sure any leftover commands are sent
		$benchmark->stop('pipeline');
	}

	$benchmark->display();