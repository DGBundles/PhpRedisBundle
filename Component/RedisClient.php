<?php
/**
 * Created by PhpStorm.
 * User: dawen
 * Date: 11.11.13
 * Time: 13:19
 */

namespace Dawen\Bundle\PhpRedisBundle\Component;

class RedisClient implements RedisClientInterface
{
    /** @var \Redis  */
    private $redis;


    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    public function __destruct()
    {
        $this->redis->close();
    }

    /**
     * CONNECTION
     * *************************************************************************************************
     */

    /**
     * Disconnects from the Redis instance, except when pconnect is used.
     *
     * @return void
     */
    public function close()
    {
        $this->redis->close();
    }

    /**
     * Switches to a given database.
     *
     * @param   int     $dbindex
     * @return  bool    TRUE in case of success, FALSE in case of failure.
     * @link    http://redis.io/commands/select
     * @example
     * <pre>
     * $redis->select(0);       // switch to DB 0
     * $redis->set('x', '42');  // write 42 to x
     * $redis->move('x', 1);    // move to DB 1
     * $redis->select(1);       // switch to DB 1
     * $redis->get('x');        // will return 42
     * </pre>
     */
    public function select($dbindex)
    {
        return $this->redis->select($dbindex);
    }

    /**
     * KEYS
     * *************************************************************************************************
     */

    /**
     * Remove specified keys.
     *
     * @param   string|array   $key1 An array of keys, or an undefined number of parameters, each a key: key1 key2 key3 ... keyN
     * @param   string      $key2 ...
     * @param   string      $key3 ...
     * @return int Number of keys deleted.
     * @link    http://redis.io/commands/del
     * @example
     * <pre>
     * $redis->set('key1', 'val1');
     * $redis->set('key2', 'val2');
     * $redis->set('key3', 'val3');
     * $redis->set('key4', 'val4');
     * $redis->delete('key1', 'key2');          // return 2
     * $redis->delete(array('key3', 'key4'));   // return 2
     * </pre>
     */
    public function del($key1, $key2 = null, $key3 = null)
    {
        if(is_array($key1))
        {
            return $this->redis->del($key1);
        }
        return $this->redis->del($key1, $key2, $key3);
    }

    /**
     * Dump a key out of a redis database, the value of which can later be passed into redis using the RESTORE command.
     * The data that comes out of DUMP is a binary representation of the key as Redis stores it.
     * @param   string  $key
     * @return  string  The Redis encoded value of the key, or FALSE if the key doesn't exist
     * @link    http://redis.io/commands/dump
     * @example
     * <pre>
     * $redis->set('foo', 'bar');
     * $val = $redis->dump('foo'); // $val will be the Redis encoded key value
     * </pre>
     */
    public function dump($key)
    {
        return $this->redis->dump($key);
    }

    /**
     * Verify if the specified key exists.
     *
     * @param   string $key
     * @return  bool: If the key exists, return TRUE, otherwise return FALSE.
     * @link    http://redis.io/commands/exists
     * @example
     * <pre>
     * $redis->set('key', 'value');
     * $redis->exists('key');               //  TRUE
     * $redis->exists('NonExistingKey');    // FALSE
     * </pre>
     */

    public function exists($key)
    {
        return $this->redis->exists($key);
    }

    /**
     * Sets an expiration date (a timeout) on an item.
     *
     * @param   string  $key    The key that will disappear.
     * @param   int     $ttl    The key's remaining Time To Live, in seconds.
     * @return  bool:   TRUE in case of success, FALSE in case of failure.
     * @link    http://redis.io/commands/expire
     * @example
     * <pre>
     * $redis->set('x', '42');
     * $redis->expire('x', 3);  // x will disappear in 3 seconds.
     * sleep(5);                    // wait 5 seconds
     * $redis->get('x');            // will return `FALSE`, as 'x' has expired.
     * </pre>
     */
    public function expire($key, $ttl)
    {
        return $this->redis->expire($key, $ttl);
    }

    /**
     * Sets an expiration date (a timestamp) on an item.
     *
     * @param   string  $key        The key that will disappear.
     * @param   int     $timestamp  Unix timestamp. The key's date of death, in seconds from Epoch time.
     * @return  bool:   TRUE in case of success, FALSE in case of failure.
     * @link    http://redis.io/commands/expireat
     * @example
     * <pre>
     * $redis->set('x', '42');
     * $now = time(NULL);               // current timestamp
     * $redis->expireAt('x', $now + 3); // x will disappear in 3 seconds.
     * sleep(5);                        // wait 5 seconds
     * $redis->get('x');                // will return `FALSE`, as 'x' has expired.
     * </pre>
     */
    public function expireAt($key, $timestamp)
    {
        return $this->redis->expireAt($key, $timestamp);
    }

    /**
     * Returns the keys that match a certain pattern.
     *
     * @param   string  $pattern pattern, using '*' as a wildcard.
     * @return  array
     */
    public function keys($pattern)
    {
        return $this->redis->keys($pattern);
    }

    /**
     * Migrates a key to a different Redis instance.
     *
     * @param   string  $host       The destination host
     * @param   int     $port       The TCP port to connect to.
     * @param   string  $key        The key to migrate.
     * @param   int     $db         The target DB.
     * @param   int     $timeout    The maximum amount of time given to this transfer.
     * @return  bool
     * @link    http://redis.io/commands/migrate
     * @example
     * <pre>
     * $redis->migrate('backup', 6379, 'foo', 0, 3600);
     * </pre>
     */
    public function migrate($host, $port, $key, $db, $timeout)
    {
        return $this->redis->migrate($host, (int) $port, $key, (int) $db, (int) $timeout);
    }

    /**
     * Moves a key to a different database.
     *
     * @param   string  $key
     * @param   int     $dbindex
     * @return  bool:   TRUE in case of success, FALSE in case of failure.
     * @link    http://redis.io/commands/move
     * @example
     * <pre>
     * $redis->select(0);       // switch to DB 0
     * $redis->set('x', '42');  // write 42 to x
     * $redis->move('x', 1);    // move to DB 1
     * $redis->select(1);       // switch to DB 1
     * $redis->get('x');        // will return 42
     * </pre>
     */
    public function move($key, $dbindex)
    {
        return $this->redis->move($key, $dbindex);
    }

    /**
     * SERVER
     * *************************************************************************************************
     */

    /**
     * Removes all entries from the current database.
     *
     * @return  bool: Always TRUE.
     * @link    http://redis.io/commands/flushdb
     * @example $redis->flushDB();
     */
    public function flushDB()
    {
        return $this->redis->flushDB();
    }

    /**
     * STRINGS
     * *************************************************************************************************
     */

    /**
     * Append specified string to the string stored in specified key.
     *
     * @param   string  $key
     * @param   string  $value
     * @return  int:    Size of the value after the append
     * @link    http://redis.io/commands/append
     * @example
     * <pre>
     * $redis->set('key', 'value1');
     * $redis->append('key', 'value2'); // 12
     * $redis->get('key');              // 'value1value2'
     * </pre>
     */
    public function append($key, $value)
    {
        return $this->redis->append($key, $value);
    }

    /**
     * Count bits in a string.
     *
     * @param   string  $key
     * @return  int     The number of bits set to 1 in the value behind the input key.
     * @link    http://redis.io/commands/bitcount
     */
    public function bitCount($key)
    {
        return $this->redis->bitCount($key);
    }

//    /**
//     * Bitwise operation on multiple keys.
//     *
//     * @param   string $operation either "AND", "OR", "NOT", "XOR"
//     * @param   string $retKey return key
//     * @param   string $key1
//     * @param   string $key2
//     * @param   null|string $key3
//     * @return  int     The size of the string stored in the destination key.
//     * @link    http://redis.io/commands/bitop
//     * @example
//     * <pre>
//     * $redis->set('bit1', '1'); // 11 0001
//     * $redis->set('bit2', '2'); // 11 0010
//     *
//     * $redis->bitOp('AND', 'bit', 'bit1', 'bit2'); // bit = 110000
//     * $redis->bitOp('OR',  'bit', 'bit1', 'bit2'); // bit = 110011
//     * $redis->bitOp('NOT', 'bit', 'bit1', 'bit2'); // bit = 110011
//     * $redis->bitOp('XOR', 'bit', 'bit1', 'bit2'); // bit = 11
//     * </pre>
//     */
//    public function bitOp($operation, $retKey, $key1, $key2, $key3 = null)
//    {
//        return $this->redis->bitOp($operation, $retKey, $key1, $key2, $key3);
//    }


    /**
     * Decrement the number stored at key by one.
     *
     * @param   string $key
     * @return  int    the new value
     * @link    http://redis.io/commands/decr
     * @example
     * <pre>
     * $redis->decr('key1'); // key1 didn't exists, set to 0 before the increment and now has the value -1
     * $redis->decr('key1'); // -2
     * $redis->decr('key1'); // -3
     * </pre>
     */
    public function decr($key)
    {
        return $this->redis->decr($key);
    }

    /**
     * Get the value related to the specified key
     *
     * @param string $key
     * @return bool|string
     */
    public function get($key)
    {
        return $this->redis->get($key);
    }

    /**
     * Return a single bit out of a larger string
     *
     * @param   string  $key
     * @param   int     $offset
     * @return  int:    the bit value (0 or 1)
     * @link    http://redis.io/commands/getbit
     * @example
     * <pre>
     * $redis->set('key', "\x7f");  // this is 0111 1111
     * $redis->getBit('key', 0);    // 0
     * $redis->getBit('key', 1);    // 1
     * </pre>
     */
    public function getBit($key, $offset)
    {
        return $this->redis->getBit($key, $offset);
    }

    /**
     * Return a substring of a larger string
     *
     * @param   string  $key
     * @param   int     $start
     * @param   int     $end
     * @return  string: the substring
     * @link    http://redis.io/commands/getrange
     * @example
     * <pre>
     * $redis->set('key', 'string value');
     * $redis->getRange('key', 0, 5);   // 'string'
     * $redis->getRange('key', -5, -1); // 'value'
     * </pre>
     */
    public function getRange($key, $start, $end)
    {
        return $this->redis->getRange($key, $start, $end);
    }

    /**
     * Sets a value and returns the previous entry at that key.
     *
     * @param   string  $key
     * @param   string  $value
     * @return  string  A string, the previous value located at this key.
     * @link    http://redis.io/commands/getset
     * @example
     * <pre>
     * $redis->set('x', '42');
     * $exValue = $redis->getSet('x', 'lol');   // return '42', replaces x by 'lol'
     * $newValue = $redis->get('x')'            // return 'lol'
     * </pre>
     */
    public function getSet($key, $value)
    {
        return $this->redis->getSet($key, $value);
    }

    /**
     * Increment the number stored at key by one.
     *
     * @param   string $key
     * @return  int    the new value
     * @link    http://redis.io/commands/incr
     * @example
     * <pre>
     * $redis->incr('key1'); // key1 didn't exists, set to 0 before the increment and now has the value 1
     * $redis->incr('key1'); // 2
     * $redis->incr('key1'); // 3
     * $redis->incr('key1'); // 4
     * </pre>
     */
    public function incr($key)
    {
        return $this->redis->incr($key);
    }

    /**
     * Increment the float value of a key by the given amount
     *
     * @param   string  $key
     * @param   float   $increment
     * @return  float
     * @link    http://redis.io/commands/incrbyfloat
     * @example
     * <pre>
     * $redis->set('x', 3);
     * var_dump( $redis->incrByFloat('x', 1.5) );   // float(4.5)
     *
     * // ! SIC
     * var_dump( $redis->get('x') );                // string(3) "4.5"
     * </pre>
     */
    public function incrByFloat($key, $increment)
    {
        return $this->redis->incrByFloat($key, $increment);
    }

    /**
     * Returns the values of all specified keys.
     *
     * For every key that does not hold a string value or does not exist,
     * the special value false is returned. Because of this, the operation never fails.
     *
     * @param array $keys
     * @return array
     * @link http://redis.io/commands/mget
     * @example
     * <pre>
     * $redis->delete('x', 'y', 'z', 'h');	// remove x y z
     * $redis->mset(array('x' => 'a', 'y' => 'b', 'z' => 'c'));
     * $redis->hset('h', 'field', 'value');
     * var_dump($redis->mget(array('x', 'y', 'z', 'h')));
     * // Output:
     * // array(3) {
     * // [0]=>
     * // string(1) "a"
     * // [1]=>
     * // string(1) "b"
     * // [2]=>
     * // string(1) "c"
     * // [3]=>
     * // bool(false)
     * // }
     * </pre>
     */
    public function mget(array $keys)
    {
        return $this->redis->mget($keys);
    }

    /**
     * Sets multiple key-value pairs in one atomic command.
     * MSETNX only returns TRUE if all the keys were set (see SETNX).
     *
     * @param   array(key => value) $array Pairs: array(key => value, ...)
     * @return  bool    TRUE in case of success, FALSE in case of failure.
     * @link    http://redis.io/commands/mset
     * @example
     * <pre>
     * $redis->mset(array('key0' => 'value0', 'key1' => 'value1'));
     * var_dump($redis->get('key0'));
     * var_dump($redis->get('key1'));
     * // Output:
     * // string(6) "value0"
     * // string(6) "value1"
     * </pre>
     */
    public function mset(array $array)
    {
        return $this->redis->mset($array);
    }

    /**
     * Set the string value in argument as value of the key.
     *
     * @param string $key
     * @param mixed $value
     * @param int $timeout
     * @return bool
     */
    public function set($key, $value, $timeout = 0)
    {
        return $this->redis->set($key, $value, (int)$timeout);
    }

    /**
     * Changes a single bit of a string.
     *
     * @param   string  $key
     * @param   int     $offset
     * @param   bool|int $value bool or int (1 or 0)
     * @return  int:    0 or 1, the value of the bit before it was set.
     * @link    http://redis.io/commands/setbit
     * @example
     * <pre>
     * $redis->set('key', "*");     // ord("*") = 42 = 0x2f = "0010 1010"
     * $redis->setBit('key', 5, 1); // returns 0
     * $redis->setBit('key', 7, 1); // returns 0
     * $redis->get('key');          // chr(0x2f) = "/" = b("0010 1111")
     * </pre>
     */
    public function setBit($key, $offset, $value)
    {
        return $this->redis->setBit($key, $offset, $value);
    }

    /**
     * Set the string value in argument as value of the key, with a time to live.
     *
     * @param   string  $key
     * @param   int     $ttl
     * @param   string  $value
     * @return  bool:   TRUE if the command is successful.
     * @link    http://redis.io/commands/setex
     * @example $redis->setex('key', 3600, 'value'); // sets key → value, with 1h TTL.
     */
    public function setex($key, $ttl, $value)
    {
        return $this->redis->setex($key, $ttl, $value);
    }

    /**
     * Set the string value in argument as value of the key if the key doesn't already exist in the database.
     *
     * @param   string  $key
     * @param   string  $value
     * @return  bool:   TRUE in case of success, FALSE in case of failure.
     * @link    http://redis.io/commands/setnx
     * @example
     * <pre>
     * $redis->setnx('key', 'value');   // return TRUE
     * $redis->setnx('key', 'value');   // return FALSE
     * </pre>
     */
    public function setnx($key, $value)
    {
        return $this->redis->setnx($key, $value);
    }

    /**
     * Changes a substring of a larger string.
     *
     * @param   string  $key
     * @param   int     $offset
     * @param   string  $value
     * @return  string: the length of the string after it was modified.
     * @link    http://redis.io/commands/setrange
     * @example
     * <pre>
     * $redis->set('key', 'Hello world');
     * $redis->setRange('key', 6, "redis"); // returns 11
     * $redis->get('key');                  // "Hello redis"
     * </pre>
     */
    public function setRange($key, $offset, $value)
    {
        return $this->redis->setRange($key, $offset, $value);
    }

    /**
     * Get the length of a string value.
     *
     * @param   string  $key
     * @return  int
     * @link    http://redis.io/commands/strlen
     * @example
     * <pre>
     * $redis->set('key', 'value');
     * $redis->strlen('key'); // 5
     * </pre>
     */
    public function strlen($key)
    {
        return $this->redis->strlen($key);
    }



}