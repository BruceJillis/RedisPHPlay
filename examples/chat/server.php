<?php
	/**
	 * A more involved example: a chat server
	 * 
	 * @package Redis\Examples
	 */
	require '..\..\library\RedisPHPlay.php';
	header('Content-Type: text/json');
	ini_set('display_errors', 0);

	$_response = array(
		'code' => 200,
		'text' => '',
		'data' => array(
		)
	);
	function respond($error, $name, $value) {
		global $_response;
		if($error) {
			$_response['code'] = intval($name);
			$_response['text'] = $value;
			echo json_encode($_response);
			die();
		} else {
			$_response['data'][$name] = $value;
		}
	}

	$redis = new RedisManager();
	try {
		$client = $redis->connect('127.0.0.1', 6379);
		$client->auth('test');
	} catch(Exception $e) {
		respond(true, 100, 'could not connect to redis');
	}

	switch($_REQUEST['action']) {
		case 'connect': 
			$count = $client->INCR('chat:users:counter');
			respond(false, 'username', "guest{$count}");
			break;
		case 'subscribe':
			$name = $_GET['channel'];
			$channel = $client->SUBSCRIBE("room:{$name}");
			set_time_limit(0);
			ini_set('implicit_flush', 1);
			for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
			ob_implicit_flush(1);
			ob_start();
			echo str_repeat(' ', 1024);
			ob_flush();
			while($channel->connected()) {
				$data = $channel->wait();
				echo json_encode(array(
					'code' => 200,
					'text' => '',
					'data' => $data
				));
				ob_flush();
			}
		break;
		case 'message': 
			// parse the message
			$username = $_GET['username'];
			$message = $_GET['message'];
			$patterns = array(
				'join' => '/^\/(\w+) #(\w+)$/i',
				'send' => '/^[\w+|\s+|_|-|.|,|;|:|<|>|@|$|%|^|&|*|(|)|+|"|\'|`|~|!|\||#|{|}|[|\]|\/|\d+]+$/i'
			);
			$found = false;
			foreach($patterns as $name => $pattern) {
				$result = array();
				preg_match($pattern, $message, $result);
				if( isset($result[0]) && ($result[0] == $message) && ($result[1] == 'join') ) {
					// join a channel
					$username = $_GET['username'];
					$message = "has joined #" . $result[2];
					$client->PUBLISH('room:' . $result[2], "<em><span class='username'>{$username}</span>&nbsp;{$message}</em>");
					respond(false, 'action', 'add-channel');
					respond(false, 'name', $result[2]);
					$found = true;
					break;
				} else if ( isset($result[0]) && ($result[0] == $message) ) {
					// send a message
					$channel = $_GET['channel'];
					$message = htmlentities($message);
					if( $channel == 'status' )
						respond(true, 102, 'cannot send in #status channel');
					$client->PUBLISH('room:' . $channel, "<span class='username'>&lt;{$username}&gt;</span>&nbsp;{$message}");
					$found = true;
					break;
				}
			}
			if( !$found )
				respond(true, 101, 'message not understood');
			break;
	}
	echo json_encode($_response);