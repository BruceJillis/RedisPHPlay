<?php
	/**
	 * A more involved example on the twitter firehose.
	 * 
	 * @package Redis\Examples
	 */
	require '..\..\library\RedisPHPlay.php';
	require '..\..\library\phirehose\lib\Phirehose.php';

	$redis = new RedisManager();
	$client = $redis->connect('127.0.0.1', 6379);
	$client->auth('test');

	class TwitterConsumer extends Phirehose {
	  /**
	   * Enqueue each status
	   *
	   * @param string $status
	   */
		public function enqueueStatus($status) {
			global $client, $count;
			$data = json_decode($status, true);
			if( is_array($data) ) {
				if( !isset($data['text']) ) {
					// deleted
				} else {
					$key = "tweet:{$data['user']['id_str']}:{$data['id_str']}";
					echo $key, ", ";
					$client->HMSET($key, 'text', $data['text']);
					$client->PUBLISH('tweets', $key);
				}
			}
			$count++;
			if( $count >= 100 ) {
				$count = 0;
				ob_flush();
			}

		}
	}

	set_time_limit(0);
	header('Content-Type: text/plain');

	ini_set('implicit_flush', 1);
	for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
	ob_implicit_flush(1);
	ob_start();
	
	echo str_repeat(' ', 1024);
	ob_flush();
	
	// start streaming
	$count = 0;
	$sc = new TwitterConsumer('user101111', '', Phirehose::METHOD_SAMPLE);
	$sc->consume();