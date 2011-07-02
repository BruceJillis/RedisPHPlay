<?php
	/**
	 * BGREWRITEAOF ~ Asynchronously rewrite the append-only file
	 *
	 * Rewrites the append-only file to reflect the current dataset in memory. If BGREWRITEAOF fails, no data gets lost as the old AOF will be untouched.
	 * 
	 * @since: 1.07
	 * @returns boolean Status code reply
	 * @package Redis\Commands\Server
	 */
	class BGREWRITEAOF extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 0);
		}
	}
	
	/**
	 * BGSAVE ~ Asynchronously save the dataset to disk
	 *
	 * Save the DB in background. The OK code is immediately returned. Redis forks, the parent continues to server the clients, the child saves the DB on disk then exit. A client my be able to check if the 
	 * operation succeeded using the LASTSAVE command.
	 * 
	 * @since: 0.07
	 * @returns boolean Status code reply
	 * @package Redis\Commands\Server
	 */
	class BGSAVE extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 0);
		}
	}

	/**
	 * CONFIG GET parameter ~ Get the value of a configuration parameter
	 *
	 * The CONFIG GET command is used to read the configuration parameters of a running Redis server. Not all the configuration parameters are supported. The symmetric command used to alter the configuration at run time is CONFIG SET.
	 * 
	 * @since: 2.0
	 * @returns array Bulk reply
	 * @package Redis\Commands\Server
	 */
	class CONFIG_GET extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 1);
		}
	}

	/**
	 * CONFIG SET parameter value ~ Set a configuration parameter to the given value
	 *
	 * The CONFIG SET command is used in order to reconfigure the server at runtime without the need to restart Redis. You can change both trivial parameters or switch from one to another persistence option using this command.
	 * The list of configuration parameters supported by CONFIG SET can be obtained issuing a CONFIG GET * command, that is the symmetrical command used to obtain information about the configuration of a running Redis instance.
	 * 
	 * @since: 2.0
	 * @returns mixed Status code reply: OK when the configuration was set properly. Otherwise an error is returned.
	 * @package Redis\Commands\Server
	 */
	class CONFIG_SET extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 2);
		}
	}

	/**
	 * CONFIG RESETSTAT ~ Reset the stats returned by INFO
	 *
	 * Resets the statistics reported by Redis using the INFO command. These are the counters that are reset: Keyspace hits, Keyspace misses, Number of commands processed, Number of connections received, Number of expired keys
	 * 
	 * Time complexity: O(1).
	 * 
	 * @since: 2.0
	 * @returns boolean Status code reply: always OK.
	 * @package Redis\Commands\Server
	 */
	class CONFIG_RESETSTAT extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 0);
		}
	}
	
	/**
	 * DBSIZE ~ Return the number of keys in the selected database
	 *
	 * Return the number of keys in the currently selected database.
	 * 
	 * @since: 0.07
	 * @returns int Integer reply: the number of keys in the database
	 * @package Redis\Commands\Server
	 */
	class DBSIZE extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 0);
		}
	}
	
	/**
	 * DEBUG OBJECT key ~ Get debugging information about a key
	 * 
	 * @since: 0.101
	 * @returns array Bulk reply: the debugging info
	 * @package Redis\Commands\Server
	 */
	class DEBUG_OBJECT extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 1);
			$this->validateKey($arguments[0]);
		}
	}
	
	/**
	 * FLUSHALL ~ Remove all keys from all databases
	 *
	 * Delete all the keys of all the existing databases, not just the currently selected one. This command never fails.
	 * 
	 * @since: 0.07
	 * @returns boolean Status code reply
	 * @package Redis\Commands\Server
	 */
	class FLUSHALL extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 0);
		}
	}
	
	/**
	 * FLUSHDB ~ Remove all keys from the current database
	 *
	 * Delete all the keys of the currently selected DB. This command never fails.
	 * 
	 * @since: 0.07
	 * @returns boolean Status code reply
	 * @package Redis\Commands\Server
	 */
	class FLUSHDB extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 0);
		}
	}

	/**
	 * INFO ~ Get information and statistics about the server
	 *
	 * The INFO command returns information and statistics about the server in format that is simple to parse by computers and easy to red by humans.
	 * 
	 * @since: 0.07
	 * @returns array Bulk reply
	 * @package Redis\Commands\Server
	 */
	class INFO extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 0);
		}
	}
	

	/**
	 * LASTSAVE ~ Get the UNIX time stamp of the last successful save to disk
	 *
	 * Return the UNIX TIME of the last DB save executed with success. A client may check if a BGSAVE command succeeded reading the LASTSAVE value, then issuing a BGSAVE command and checking at regular intervals every N 
	 * seconds if LASTSAVE changed.
	 * 
	 * @since: 0.07
	 * @returns int Integer reply: a unix timestamp.
	 * @package Redis\Commands\Server
	 */
	class LASTSAVE extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 0);
		}
	}
	
	/**
	 * MONITOR ~ Listen for all requests received by the server in real time
	 *
	 * MONITOR is a debugging command that outputs the whole sequence of commands received by the Redis server. is very handy in order to understand what is happening into the database. This command is used directly via telne
	 * In order to end a monitoring session just issue a QUIT command by hand.
	 * 
	 * @since: 0.07
	 * @returns mixed Non standard return value, just dumps the received commands in an infinite flow.
	 * @package Redis\Commands\Server
	 */
	class MONITOR extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 0);
		}
	}
	
	/**
	 * SAVE ~ Synchronously save the dataset to disk
	 * 
	 * @since: 0.07
	 * @package Redis\Commands\Server
	 */
	class SAVE extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 0);
		}
	}
	
	/**
	 * SHUTDOWN ~ Synchronously save the dataset to disk and then shut down the server
	 * 
	 * Stop all the clients, save the DB, then quit the server. This commands makes sure that the DB is switched off without the lost of any data. This is not guaranteed if the client uses simply SAVE and then 
	 * QUIT because other clients may alter the DB data between the two commands.
	 * 
	 * @since: 0.07
	 * @returns boolean Status code reply on error. On success nothing is returned since the server quits and the connection is closed.
	 * @package Redis\Commands\Server
	 */
	class SHUTDOWN extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 0);
		}
	}
	
	/**
	 * SLAVEOF host port ~ Make the server a slave of another instance, or promote it as master
	 * 
	 * The SLAVEOF command can change the replication settings of a slave on the fly. If a Redis server is already acting as slave, the command SLAVEOF NO ONE will turn off the replication turning the Redis server into a MASTER. 
	 * In the proper form SLAVEOF hostname port will make the server a slave of the specific server listening at the specified hostname and port.
	 * If a server is already a slave of some master, SLAVEOF hostname port will stop the replication against the old server and start the synchronization against the new one discarding the old dataset.
	 * The form SLAVEOF no one will stop replication turning the server into a MASTER but will not discard the replication. So if the old master stop working it is possible to turn the slave into a master and set the 
	 * application to use the new master in read/write. Later when the other Redis server will be fixed it can be configured in order to work as slave.
	 * 
	 * @since: 0.100
	 * @returns boolean	Status code reply
	 * @package Redis\Commands\Server
	 */
	class SLAVEOF extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 0);
		}
	}
	
	/**
	 * SLOWLOG subcommand [argument] ~ Manages the Redis slow queries log
	 * 
	 * The Redis Slow Log is a system to log queries that exceeded a specified execution time. The execution time does not include the I/O operations like 
	 * talking with the client, sending the reply and so forth, but just the time needed to actually execute the command (this is the only stage of command execution where the thread is blocked and can not serve other requests 
	 * in the meantime).
	 * 
	 * @since: 2.2.12
	 * @returns boolean	Status code reply
	 * @package Redis\Commands\Server
	 */
	class SLOWLOG extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThenEqual(count($arguments), 1);
		}
	}