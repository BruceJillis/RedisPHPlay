<?php
	/**
	 * Example showing basic pub sub functionality .. open 2 browser tabs to publish and subscribe at the same time
	 * 
	 * @package Redis\Examples
	 */
	require '..\library\RedisPHPlay.php';

	$redis = new RedisManager();
	$client = $redis->connect('127.0.0.1', 6379);
	$client->auth('test');

	$message = 'message ' . intval(rand(1, 11111));
	$channel = '#room';
	if( isset($_REQUEST['channel']) ) {
		$channel = $_REQUEST['channel'];
	}
	switch(@$_REQUEST['action']) {
		case 'subscribe':
			echo "<h2>Listening for messages on <em>'" .$_REQUEST['channel']. "'</em></h2>";
			set_time_limit(0);
			ini_set('implicit_flush', 1);
			for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
			ob_implicit_flush(1);
			ob_start();
			echo str_repeat(' ', 1024);
			echo "<pre>";
			ob_flush();
			$channel = $client->SUBSCRIBE($_REQUEST['channel']);
			while($channel->connected()) {
				$message = $channel->wait();				
				echo "{$message[1]}: '$message[2]' \n";
				if( $message[2] == 'quit' ) {
					$channel->close();
				}
				ob_flush();
			}
			die('finished');
		case 'publish':
			$client->PUBLISH($_REQUEST['channel'], $_REQUEST['message']);
		default:
			?>
<p>Open 2 browser windows (at least) and set 1 to SUBSCRIBE to the a channel (currently <em>'<?= $channel; ?>'</em>). Use the other window to send messages to the channel (currently <em>'<?= $message; ?>'</em>). Enjoy. When you're done you can make the SUBSCRIBED window quit by sending quit to the channel (second Send form).</p>
<h2>Publish</h2>
<form>
	<input name="action" type="hidden" value="publish" />
	<input name="channel" type="text" value="<?= $channel; ?>" />
	<input name="message" type="text" value="<?= $message; ?>" />
	<input type="submit" value="send" />
</form>

<h2>Send Quit</h2>
<form>
	<input name="action" type="hidden" value="publish" />
	<input name="channel" type="text" value="<?= $channel; ?>" />
	<input name="message" type="text" value="quit" />
	<input type="submit" value="quit" />
</form>

<h2>Subscribe</h2>
<form>
	<input name="action" type="hidden" value="subscribe" />
	<input name="channel" type="text" value="<?= $channel; ?>" />
	<input type="submit" value="send" />
</form>
<?php
	}