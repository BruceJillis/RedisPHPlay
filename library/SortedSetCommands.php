<?php
	/**
	 * ZADD key score member ~ Add a member to a sorted set, or update its score if it already exists
	 *
	 * Adds the member with the specified score to the sorted set stored at key. If member is already a member of the sorted set, the score is updated and the element reinserted at the right position to ensure the 
	 * correct ordering. If key does not exist, a new sorted set with the specified member as sole member is created. If the key exists but does not hold a sorted set, an error is returned. The score value should 
	 * be the string representation of a numeric value, and accepts double precision floating point numbers.
	 * 
	 * Time complexity: O(log(N)) where N is the number of elements in the sorted set.
	 * 
	 * @since: 1.1
	 * @returns int Integer reply, specifically: 1 if the element was added. 0 if the element was already a member of the sorted set and the score was updated.
	 * @package Redis\Commands\SortedSets
	 */
	class ZADD extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 3);
			$this->validateKey($arguments[0]);
			$this->validateNumber($arguments[1]);
		}
	}

	/**
	 * ZCARD key ~ Get the number of members in a sorted set
	 *
	 * Returns the sorted set cardinality (number of elements) of the sorted set stored at key.
	 * 
	 * Time complexity: O(1).
	 * 
	 * @since: 1.1
	 * @returns int Integer reply: the cardinality (number of elements) of the sorted set, or 0 if key does not exist.
	 * @package Redis\Commands\SortedSets
	 */
	class ZCARD extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 1);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * ZCOUNT key min max ~ Count the members in a sorted set with scores within the given values
	 *
	 * Returns the number of elements in the sorted set at key with a score between min and max. The min and max arguments have the same semantic as described for ZRANGEBYSCORE.
	 * 
	 * Time complexity: O(log(N)+M) with N being the number of elements in the sorted set and M being the number of elements between min and max.
	 * 
	 * @since: 1.3.3
	 * @returns int Integer reply: the number of elements in the specified score range.
	 * @package Redis\Commands\SortedSets
	 */
	class ZCOUNT extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 3);
			$this->validateKey($arguments[0]);
			$this->validateInt($arguments[1]);
			$this->validateInt($arguments[2]);
		}
	}

	/**
	 * ZINCRBY key increment member ~ Increment the score of a member in a sorted set
	 *
	 * Increments the score of member in the sorted set stored at key by increment. If member does not exist in the sorted set, it is added with increment as its score (as if its previous score was 0.0). If key does not exist, 
	 * a new sorted set with the specified member as its sole member is created.
	 * An error is returned when key exists but does not hold a sorted set.
	 * The score value should be the string representation of a numeric value, and accepts double precision floating point numbers. It is possible to provide a negative value to decrement the score.
	 * 
	 * Time complexity: O(log(N)) where N is the number of elements in the sorted set.
	 * 
	 * @since: 1.1
	 * @returns array Bulk reply: the new score of member (a double precision floating point number), represented as string.
	 * @package Redis\Commands\SortedSets
	 */
	class ZINCRBY extends RedisCommand {
		function validate(&$arguments) {
			$this->validateEquals(count($arguments), 3);
			$this->validateKey($arguments[0]);
			$this->validateInt($arguments[1]);
		}
	}

	/**
	 * ZINTERSTORE destination numkeys key [key ...] [WEIGHTS weight [weight ...]] [AGGREGATE SUM|MIN|MAX] ~ Intersect multiple sorted sets and store the resulting sorted set in a new key
	 *
	 * Computes the intersection of numkeys sorted sets given by the specified keys, and stores the result in destination. It is mandatory to provide the number of input keys (numkeys) before passing the input keys and 
	 * the other (optional) arguments.
	 * By default, the resulting score of an element is the sum of its scores in the sorted sets where it exists. Because intersection requires an element to be a member of every given sorted set, this results in the score 
	 * of every element in the resulting sorted set to be equal to the number of input sorted sets.
	 * For a description of the WEIGHTS and AGGREGATE options, see ZUNIONSTORE.
	 * If destination already exists, it is overwritten.
	 * 
	 * Time complexity: O(N*K)+O(M*log(M)) worst case with N being the smallest input sorted set, K being the number of input sorted sets and M being the number of elements in the resulting sorted set.
	 * 
	 * @since: 1.3.10
	 * @returns int Integer reply: the number of elements in the resulting sorted set at destination.
	 * @package Redis\Commands\SortedSets
	 */
	class ZINTERSTORE extends RedisCommand {
		function validate(&$arguments) {
			// todo
		}
	}

	/**
	 * ZRANGE key start stop [WITHSCORES] ~ Return a range of members in a sorted set, by index
	 *
 	 * Returns the specified range of elements in the sorted set stored at key. The elements are considered to be ordered from the lowest to the highest score. Lexicographical order is used for elements with equal score.
	 * See ZREVRANGE when you need the elements ordered from highest to lowest score (and descending lexicographical order for elements with equal score).
	 * Both start and stop are zero-based indexes, where 0 is the first element, 1 is the next element and so on. They can also be negative numbers indicating offsets from the end of the sorted set, with -1 being the last element 
	 * of the sorted set, -2 the penultimate element and so on.
	 * Out of range indexes will not produce an error. If start is larger than the largest index in the sorted set, or start > stop, an empty list is returned. If stop is larger than the end of the sorted set Redis will treat it 
	 * like it is the last element of the sorted set.
	 * It is possible to pass the WITHSCORES option in order to return the scores of the elements together with the elements. The returned list will contain value1,score1,...,valueN,scoreN instead of value1,...,valueN. 
	 * Client libraries are free to return a more appropriate data type (suggestion: an array with (value, score) arrays/tuples).
	 * 
	 * Time complexity: O(log(N)+M) with N being the number of elements in the sorted set and M the number of elements returned.
	 * 
	 * @since: 1.1
	 * @returns array Multi-bulk reply: list of elements in the specified range (optionally with their scores).
	 * @package Redis\Commands\SortedSets
	 */
	class ZRANGE extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThenEqual(count($arguments), 3);
			$this->validateKey($arguments[0]);
			$this->validateInt($arguments[1]);
			$this->validateInt($arguments[2]);
			if( isset($arguments[3]) ) {
				$arguments[3] = strtoupper($arguments[3]);
				$this->validateEnumerate($arguments[3], array('WITHSCORES'));
			}
		}
	}

	/**
	 * ZRANGEBYSCORE key min max [WITHSCORES] [LIMIT offset count] ~ Return a range of members in a sorted set, by score
	 *
 	 * Returns all the elements in the sorted set at key with a score between min and max (including elements with score equal to min or max). The elements are considered to be ordered from low to high scores.
	 * The elements having the same score are returned in lexicographical order (this follows from a property of the sorted set implementation in Redis and does not involve further computation).
	 * The optional LIMIT argument can be used to only get a range of the matching elements (similar to SELECT LIMIT offset, count in SQL). Keep in mind that if offset is large, the sorted set needs to be traversed for 
	 * offset elements before getting to the elements to return, which can add up to O(N) time complexity.
	 * The optional WITHSCORES argument makes the command return both the element and its score, instead of the element alone. This option is available since Redis 2.0.
	 * 
	 * Time complexity: O(log(N)+M) with N being the number of elements in the sorted set and M the number of elements being returned. If M is constant (e.g. always asking for the first 10 elements with LIMIT), you can 
	 * consider it O(log(N)).
	 * 
	 * @since: 1.050
	 * @returns array Multi-bulk reply: list of elements in the specified score range (optionally with their scores).
	 * @package Redis\Commands\SortedSets
	 */
	class ZRANGEBYSCORE extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThenEqual(count($arguments), 3);
			$this->validateKey($arguments[0]);
			$this->validateInt($arguments[1]);
			$this->validateInt($arguments[2]);
			if( isset($arguments[3]) ) {
				$arguments[3] = strtoupper($arguments[3]);
				$this->validateEnumerate($arguments[3], array('WITHSCORES', 'LIMIT'));
			}
		}
	}

	/**
	 * ZRANK key member ~ Determine the index of a member in a sorted set
	 *
	 * Returns the rank of member in the sorted set stored at key, with the scores ordered from low to high. The rank (or index) is 0-based, which means that the member with the lowest score has rank 0. Use ZREVRANK to get 
	 * the rank of an element with the scores ordered from high to low.
	 * 
	 * Time complexity: O(log(N))
	 * 
	 * @since: 1.3.4
	 * @returns mixed If member exists in the sorted set, Integer reply: the rank of member. If member does not exist in the sorted set or key does not exist, Bulk reply: nil.
	 * @package Redis\Commands\SortedSets
	 */
	class ZRANK extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThenEqual(count($arguments), 2);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * ZREM key member ~ Remove a member from a sorted set
	 *
	 * Removes the member from the sorted set stored at key. If member is not a member of the sorted set, no operation is performed. An error is returned when key exists and does not hold a sorted set
	 * 
	 * Time complexity: O(log(N)) with N being the number of elements in the sorted set.
	 * 
	 * @since: 1.1
	 * @returns int Integer reply, specifically: 1 if member was removed. 0 if member is not a member of the sorted set.
	 * @package Redis\Commands\SortedSets
	 */
	class ZREM extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThenEqual(count($arguments), 2);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * ZREMRANGEBYRANK key start stop ~ Remove all members in a sorted set within the given indexes
	 *
	 * Removes all elements in the sorted set stored at key with rank between start and stop. Both start and stop are 0-based indexes with 0 being the element with the lowest score. These indexes can be negative 
	 * numbers, where they indicate offsets starting at the element with the highest score. For example: -1 is the element with the highest score, -2 the element with the second highest score and so forth.
	 * 
	 * Time complexity: O(log(N)+M) with N being the number of elements in the sorted set and M the number of elements removed by the operation.
	 * 
	 * @since: 1.3.4
	 * @returns int Integer reply: the number of elements removed.
	 * @package Redis\Commands\SortedSets
	 */
	class ZREMRANGEBYRANK extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThenEqual(count($arguments), 2);
			$this->validateKey($arguments[0]);
			$this->validateInt($arguments[1]);
			$this->validateInt($arguments[2]);
		}
	}

	/**
	 * ZREMRANGEBYSCORE key min max ~ Remove all members in a sorted set within the given scores
	 *
	 * Removes all elements in the sorted set stored at key with a score between min and max (inclusive). Since version 2.1.6, min and max can be exclusive, following the syntax of ZRANGEBYSCORE.
	 * 
	 * Time complexity: O(log(N)+M) with N being the number of elements in the sorted set and M the number of elements removed by the operation.
	 * 
	 * @since: 1.1
	 * @returns int Integer reply: the number of elements removed.
	 * @package Redis\Commands\SortedSets
	 */
	class ZREMRANGEBYSCORE extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThenEqual(count($arguments), 2);
			$this->validateKey($arguments[0]);
			$this->validateNumber($arguments[1]);
			$this->validateNumber($arguments[2]);
		}
	}

	/**
	 * ZREVRANGE key start stop [WITHSCORES] ~ Return a range of members in a sorted set, by index, with scores ordered from high to low
	 *
 	 * Returns the specified range of elements in the sorted set stored at key. The elements are considered to be ordered from the highest to the lowest score. Descending lexicographical order is used for elements with 
	 * equal score. Apart from the reversed ordering, ZREVRANGE is similar to ZRANGE.
	 * 
	 * Time complexity: O(log(N)+M) with N being the number of elements in the sorted set and M the number of elements returned.
	 * 
	 * @since: 1.1
	 * @returns array Multi-bulk reply: list of elements in the specified range (optionally with their scores).
	 * @package Redis\Commands\SortedSets
	 */
	class ZREVRANGE extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThenEqual(count($arguments), 3);
			$this->validateKey($arguments[0]);
			$this->validateInt($arguments[1]);
			$this->validateInt($arguments[2]);
			if( isset($arguments[3]) ) {
				$arguments[3] = strtoupper($arguments[3]);
				$this->validateEnumerate($arguments[3], array('WITHSCORES'));
			}
		}
	}

	/**
	 * ZREVRANGEBYSCORE key max min [WITHSCORES] [LIMIT offset count] ~ Return a range of members in a sorted set, by score, with scores ordered from high to low
	 *
 	 * Returns all the elements in the sorted set at key with a score between max and min (including elements with score equal to max or min). In contrary to the default ordering of sorted sets, for this command the 
	 * elements are considered to be ordered from high to low scores. The elements having the same score are returned in reverse lexicographical order. Apart from the reversed ordering, ZREVRANGEBYSCORE is similar 
	 * to ZRANGEBYSCORE.
	 * 
	 * Time complexity: O(log(N)+M) with N being the number of elements in the sorted set and M the number of elements being returned. If M is constant (e.g. always asking for the first 10 elements with LIMIT), you can 
	 * consider it O(log(N)).
	 * 
	 * @since: 2.1.6
	 * @returns array Multi-bulk reply: list of elements in the specified range (optionally with their scores).
	 * @package Redis\Commands\SortedSets
	 */
	class ZREVRANGEBYSCORE extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThenEqual(count($arguments), 3);
			$this->validateKey($arguments[0]);
			$this->validateNumber($arguments[1]);
			$this->validateNumber($arguments[2]);
		}
	}

	/**
	 * ZREVRANK key member ~ Determine the index of a member in a sorted set, with scores ordered from high to low
	 *
 	 * Returns the rank of member in the sorted set stored at key, with the scores ordered from high to low. The rank (or index) is 0-based, which means that the member with the highest score has 
	 * rank 0. Use ZRANK to get the rank of an element with the scores ordered from low to high.
	 * 
	 * Time complexity: O(log(N))
	 * 
	 * @since: 1.3.4
	 * @returns mixed If member exists in the sorted set, Integer reply: the rank of member. If member does not exist in the sorted set or key does not exist, Bulk reply: nil.
	 * @package Redis\Commands\SortedSets
	 */
	class ZREVRANK extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThenEqual(count($arguments), 2);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * ZSCORE key member ~ Get the score associated with the given member in a sorted set
	 *
 	 * Returns the score of member in the sorted set at key. If member does not exist in the sorted set, or key does not exist, nil is returned.
	 * 
	 * Time complexity: O(1)
	 * 
	 * @since: 1.1
	 * @returns array Bulk reply: the score of member (a double precision floating point number), represented as string.
	 * @package Redis\Commands\SortedSets
	 */
	class ZSCORE extends RedisCommand {
		function validate(&$arguments) {
			$this->validateLargerThenEqual(count($arguments), 2);
			$this->validateKey($arguments[0]);
		}
	}

	/**
	 * ZUNIONSTORE destination numkeys key [key ...] [WEIGHTS weight [weight ...]] [AGGREGATE SUM|MIN|MAX] ~ Add multiple sorted sets and store the resulting sorted set in a new key
	 *
 	 * Computes the union of numkeys sorted sets given by the specified keys, and stores the result in destination. It is mandatory to provide the number of input keys (numkeys) before passing the input keys and the 
	 * other (optional) arguments.
	 * 
	 * Time complexity: O(N)+O(M log(M)) with N being the sum of the sizes of the input sorted sets, and M being the number of elements in the resulting sorted set
	 * 
	 * @since: 1.3.10
	 * @returns array Bulk reply: the score of member (a double precision floating point number), represented as string.
	 * @package Redis\Commands\SortedSets
	 */
	class ZUNIONSTORE extends RedisCommand {
		function validate(&$arguments) {
			// todo: validation
		}
	}