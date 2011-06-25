<?php
	require '..\..\library\RedisPHPlay.php';

	set_time_limit(0);
	// header('Content-Type: text/plain');

	ini_set('implicit_flush', 1);
	for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
	ob_implicit_flush(1);
	ob_start();
	
	echo str_repeat(' ', 1024);
	ob_flush();

	$redis = new RedisManager();
	$client = $redis->connect('127.0.0.1', 6379);
	$client->auth('test');
	if( !isset($_GET['send']) ) {
		$channel = $client->SUBSCRIBE('tweets');
		while( $message == $channel->wait() ) {
			print_r($message);
			ob_flush();
		}
	} else {
		$rnd = intval(rand(0, 1000));
		echo 'sending', $_GET['send'], $rnd;
		$client->PUBLISH('tweets', $rnd . ' - ' . $_GET['send']);
	}