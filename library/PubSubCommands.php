<?php
	/**
	 * PSUBSCRIBE pattern [pattern ...] ~ Listen for messages published to channels matching the given patterns
	 *
	 * Subscribes the client to the specified channels.
	 * 
	 * Time complexity: O(N) where N is the number of patterns the client is already subscribed to.
	 * 
	 * @since: 1.3.8
	 * @package Redis\Commands\PubSub
	 */
	class PSUBSCRIBE extends RedisCommand {
		public $persistent = true;

		function validate(&$arguments) {
			$this->validateLargerThenEqual(count($arguments), 1);
		}
	}
	
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
	}

	/**
	 * PUNSUBSCRIBE [pattern [pattern ...]] ~ Stop listening for messages posted to channels matching the given patterns
	 *
	 * Unsubscribes the client from the given patterns, or from all of them if none is given.
	 * 
	 * When no patters are specified, the client is unsubscribed from all the previously subscribed patterns. In this case, a message for every unsubscribed pattern will be sent to the client.
	 * 
	 * Time complexity: O(N+M) where N is the number of patterns the client is already subscribed and M is the number of total patterns subscribed in the system (by any client).
	 * 
	 * @since: 1.3.8
	 * @package Redis\Commands\PubSub
	 */
	class PUNSUBSCRIBE extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThenEqual(count($arguments), 0);
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
	 * @package Redis\Commands\PubSub
	 */
	class PUBLISH extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThenEqual(count($arguments), 1);
		}
	}

	/**
	 * UNSUBSCRIBE [channel [channel ...]] ~ Stop listening for messages posted to the given channels
	 *
	 * Unsubscribes the client from the given channels, or from all of them if none is given.
	 * 
	 * When no channels are specified, the client is unsubscribed from all the previously subscribed channels. In this case, a message for every unsubscribed channel will be sent to the client.
	 * 
	 * Time complexity: O(N) where N is the number of clients already subscribed to a channel.
	 * 
	 * @since: 1.3.8
	 * @package Redis\Commands\PubSub
	 */
	class UNSUBSCRIBE extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThenEqual(count($arguments), 0);
		}
	}
	