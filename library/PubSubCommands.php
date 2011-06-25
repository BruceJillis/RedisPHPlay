<?php
	/**
	 * SUBSCRIBE channel [channel ...] ~ Listen for messages published to the given channels
	 *
	 * Subscribes the client to the specified channels.
	 *
	 * Once the client enters the subscribed state it is not supposed to issue any other commands, expect for additional SUBSCRIBE, PSUBSCRIBE, UNSUBSCRIBE and PUNSUBSCRIBE commands.
	 * 
	 * Time complexity: O(N) where N is the number of channels to subscribe to.
	 * 
	 * @since: 1.3.8
	 * @package Redis\Commands\PubSub
	 */
	class SUBSCRIBE extends RedisCommand {
		public $persistent = true;

		function validate(&$arguments) {
			$this->validateLargerThenEqual(count($arguments), 1);
		}

		function output($line) {
			print_r($line);
		}
	}
	
	/**
	 * PUBLISH channel message ~ Post a message to a channel
	 * 
	 * Posts a message to the given channel.
	 * 
	 * Time complexity: O(N+M) where N is the number of clients subscribed to the receiving channel and M is the total number of subscribed patterns (by any client).
	 *
	 * @since: 1.3.8
	 * @returns int Integer reply: the number of clients that received the message.
	 */
	class PUBLISH extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThenEqual(count($arguments), 1);
		}
	}