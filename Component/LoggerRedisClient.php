<?php
/**
 * Created by PhpStorm.
 * User: dawen
 * Date: 14.11.13
 * Time: 08:09
 */

namespace Dawen\Bundle\PhpRedisBundle\Component;

use Dawen\Bundle\PhpRedisBundle\DataCollector\RedisDataCollectorInterface;
use Psr\Log\LoggerInterface;

class LoggerRedisClient implements RedisClientInterface
{
    /** @var RedisClientInterface  */
    private $redis;

    /** @var LoggerInterface */
    private $logger;

    /** @var  array */
    private $config;

    /** @var RedisDataCollectorInterface  */
    private $collector;

    /**
     * @param RedisClientInterface $redis
     * @param LoggerInterface $logger
     * @param RedisDataCollectorInterface $collector
     * @param array $config
     */
    public function __construct(RedisClientInterface $redis,
                                LoggerInterface $logger,
                                RedisDataCollectorInterface $collector,
                                array $config)
    {
        $this->redis = $redis;
        $this->logger = $logger;
        $this->config = $config;
        $this->collector = $collector;
    }

    /**
     * phpredis functionality
     * ***************************************************************************************************************
     */

    /**
     * CONNECTION
     * *************************************************************************************************
     */

    /**
     * @inheritdoc
     */
    public function auth($password)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->auth($password);
        $duration = $this->endMeasure($startTime);

        $params = array('password' => $password);
        if(false === $result)
        {
            $this->warning('auth', $duration, $params);
        }
        else
        {
            $this->logInfo('auth', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function close()
    {
        $startTime = $this->startMeasure();
        $this->redis->close();
        $duration = $this->endMeasure($startTime);

        $params = array();
        $this->logInfo('close', $duration, $params);
    }

    /**
     * @inheritdoc
     */
    public function cEcho($value)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->cEcho($value);
        $duration = $this->endMeasure($startTime);

        $params = array('value' => $value);
        if(false === $result)
        {
            $this->warning('cEcho', $duration, $params);
        }
        else
        {
            $this->logInfo('cEcho', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function getOption($name)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->getOption($name);
        $duration = $this->endMeasure($startTime);

        $params = array('name' => $name);
        if(false === $result)
        {
            $this->warning('getOption', $duration, $params);
        }
        else
        {
            $this->logInfo('getOption', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function ping()
    {
        $startTime = $this->startMeasure();
        $exception = null;
        $result = null;
        try {
            $result = $this->redis->ping();
        } catch(\Exception $e) {
            $exception = $e;
        }

        $duration = $this->endMeasure($startTime);

        if(null !== $exception)
        {
            $this->warning('ping', $duration, array());
        }
        else
        {
            $this->logInfo('ping', $duration, array());
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function select($dbindex)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->select($dbindex);
        $duration = $this->endMeasure($startTime);

        $params = array('dbindex' => $dbindex);
        if(false === $result)
        {
            $this->warning('select', $duration, $params);
        }
        else
        {
            $this->logInfo('select', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function setOption($name, $value)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->setOption($name, $value);
        $duration = $this->endMeasure($startTime);

        $params = array('name' => $name, 'value' => $value);
        if(false === $result)
        {
            $this->warning('setOption', $duration, $params);
        }
        else
        {
            $this->logInfo('setOption', $duration, $params);
        }

        return $result;
    }

    /**
     * HASHES
     * *************************************************************************************************
     */

    /**
     * Removes a values from the hash stored at key.
     * If the hash table doesn't exist, or the key doesn't exist, FALSE is returned.
     *
     * @param   string  $key
     * @param   string  $hashKey1
     * @param   string  $hashKey2
     * @param   string  $hashKeyN
     * @return  int     Number of deleted fields
     * @link    http://redis.io/commands/hdel
     * @example
     * <pre>
     * $redis->hMSet('h',
     *               array(
     *                    'f1' => 'v1',
     *                    'f2' => 'v2',
     *                    'f3' => 'v3',
     *                    'f4' => 'v4',
     *               ));
     *
     * var_dump( $redis->hDel('h', 'f1') );        // int(1)
     * var_dump( $redis->hDel('h', 'f2', 'f3') );  // int(2)
     * s
     * var_dump( $redis->hGetAll('h') );
     * //// Output:
     * //  array(1) {
     * //    ["f4"]=> string(2) "v4"
     * //  }
     * </pre>
     */
    public function hDel($key, $hashKey1, $hashKey2 = null, $hashKeyN = null)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->hDel($key, $hashKey1, $hashKey2, $hashKeyN);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'hashKey1' => $hashKey1);
        if(null !== $hashKey2) {
            $params['hashKey2'] = $hashKey2;
        }
        if(null !== $hashKeyN) {
            $params['hashKeyN'] = $hashKeyN;
        }

        if(false === $result)
        {
            $this->warning('hDel', $duration, $params);
        }
        else
        {
            $this->logInfo('hDel', $duration, $params);
        }

        return $result;
    }

    /**
     * Verify if the specified member exists in a key.
     *
     * @param   string  $key
     * @param   string  $hashKey
     * @return  bool:   If the member exists in the hash table, return TRUE, otherwise return FALSE.
     * @link    http://redis.io/commands/hexists
     * @example
     * <pre>
     * $redis->hSet('h', 'a', 'x');
     * $redis->hExists('h', 'a');               //  TRUE
     * $redis->hExists('h', 'NonExistingKey');  // FALSE
     * </pre>
     */
    public function hExists($key, $hashKey)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->hExists($key, $hashKey);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'hashKey' => $hashKey);
        $this->logInfo('hExists', $duration, $params);

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function hGet($key, $hashKey)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->hGet($key, $hashKey);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'hashKey' => $hashKey);
        if(false === $result)
        {
            $this->warning('hGet', $duration, $params);
        }
        else
        {
            $this->logInfo('hGet', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function hGetAll($key)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->hGetAll($key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);
        if(false === $result)
        {
            $this->warning('hGetAll', $duration, $params);
        }
        else
        {
            $this->logInfo('hGetAll', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function hIncrBy($key, $hashKey, $value)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->hIncrBy($key, $hashKey, $value);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'hashKey' => $hashKey, 'value' => $value);
        if(false === $result)
        {
            $this->warning('hIncrBy', $duration, $params);
        }
        else
        {
            $this->logInfo('hIncrBy', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function hIncrByFloat($key, $field, $increment)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->hIncrByFloat($key, $field, $increment);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'field' => $field, 'increment' => $increment);
        if(false === $result)
        {
            $this->warning('hIncrByFloat', $duration, $params);
        }
        else
        {
            $this->logInfo('hIncrByFloat', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function hKeys($key)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->hKeys($key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);
        if(false === $result)
        {
            $this->warning('hKeys', $duration, $params);
        }
        else
        {
            $this->logInfo('hKeys', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function hLen($key)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->hLen($key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);
        if(false === $result)
        {
            $this->warning('hLen', $duration, $params);
        }
        else
        {
            $this->logInfo('hLen', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function hMGet($key, array $hashKeys)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->hMGet($key, $hashKeys);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'hashKeys' => $hashKeys);
        if(false === $result)
        {
            $this->warning('hMGet', $duration, $params);
        }
        else
        {
            $this->logInfo('hMGet', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function hMSet($key, array $hashKeys)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->hMSet($key, $hashKeys);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'hashKeys' => array_keys($hashKeys));
        if(false === $result)
        {
            $this->warning('hMSet', $duration, $params);
        }
        else
        {
            $this->logInfo('hMSet', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function hSet($key, $hashKey, $value)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->hSet($key, $hashKey, $value);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'hashKey' => $hashKey);
        if(false === $result)
        {
            $this->warning('hSet', $duration, $params);
        }
        else
        {
            $this->logInfo('hSet', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function hSetNx($key, $hashKey, $value)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->hSetNx($key, $hashKey, $value);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'hashKey' => $hashKey);
        if(false === $result)
        {
            $this->warning('hSetNx', $duration, $params);
        }
        else
        {
            $this->logInfo('hSetNx', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function hVals($key)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->hVals($key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);
        if(false === $result)
        {
            $this->warning('hVals', $duration, $params);
        }
        else
        {
            $this->logInfo('hVals', $duration, $params);
        }

        return $result;
    }

    /**
     * KEYS
     * *************************************************************************************************
     */

    /**
     * @inheritdoc
     */
    public function del($key1, $key2 = null,$key3 = null)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->del($key1, $key2, $key3);
        $duration = $this->endMeasure($startTime);

        $params = array('key1' => $key1
                        , 'key2' => $key2
                        , 'key3' => $key3
                        , 'result' => $result);

        $this->logInfo('del', $duration, $params);

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function dump($key)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->dump($key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);
        if(false === $result)
        {
            $this->warning('dump', $duration, $params);
        }
        else
        {
            $this->logInfo('dump', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function exists($key)
    {
        $startTime = $this->startMeasure();
        $success = $this->redis->exists($key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);

        $this->logInfo('exists', $duration, $params);

        return $success;
    }

    /**
     * @inheritdoc
     */
    public function expire($key, $ttl)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->expire($key, $ttl);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key
                        , 'ttl' => $ttl);

        if(false === $result)
        {
            $this->warning('expire', $duration, $params);
        }
        else
        {
            $this->logInfo('expire', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function expireAt($key, $timestamp)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->expireAt($key, $timestamp);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key
                        , 'timestamp' => $timestamp);

        if(false === $result)
        {
            $this->warning('expire', $duration, $params);
        }
        else
        {
            $this->logInfo('expire', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function keys($pattern)
    {
        $startTime = $this->startMeasure();
        $strings = $this->redis->keys($pattern);
        $duration = $this->endMeasure($startTime);

        $params = array('pattern' => $pattern);
        $this->logInfo('keys', $duration, $params);

        return $strings;
    }

    /**
     * @inheritdoc
     */
    public function migrate($host, $port, $key, $db, $timeout)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->migrate($host, $port, $key, $db, $timeout);
        $duration = $this->endMeasure($startTime);

        $params = array('host' => $host
                        , 'port' => $port
                        , 'key' => $key
                        , 'db' => $db
                        , 'timeout' => $timeout);

        if(false === $result)
        {
            $this->warning('migrate', $duration, $params);
        }
        else
        {
            $this->logInfo('migrate', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function move($key, $dbindex)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->move($key, $dbindex);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key
                        , 'dbindex' => $dbindex);

        if(false === $result)
        {
            $this->warning('move', $duration, $params);
        }
        else
        {
            $this->logInfo('move', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function object($string, $key)
    {

        $startTime = $this->startMeasure();
        $result = $this->redis->object($string, $key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key
                        , 'string' => $string);

        if(false === $result)
        {
            $this->warning('object', $duration, $params);
        }
        else
        {
            $this->logInfo('object', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function persist($key)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->persist($key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);

        if(false === $result)
        {
            $this->warning('persist', $duration, $params);
        }
        else
        {
            $this->logInfo('persist', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function randomKey()
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->randomKey();
        $duration = $this->endMeasure($startTime);

        $params = array();

        if(false === $result)
        {
            $this->warning('randomKey', $duration, $params);
        }
        else
        {
            $this->logInfo('randomKey', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function rename($srcKey, $dstKey)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->rename($srcKey, $dstKey);
        $duration = $this->endMeasure($startTime);

        $params = array('srcKey' => $srcKey
                        , 'dstKey' => $dstKey);

        if(false === $result)
        {
            $this->warning('rename', $duration, $params);
        }
        else
        {
            $this->logInfo('rename', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function renameNx($srcKey, $dstKey)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->renameNx($srcKey, $dstKey);
        $duration = $this->endMeasure($startTime);

        $params = array('srcKey' => $srcKey
                        , 'dstKey' => $dstKey);

        if(false === $result)
        {
            $this->warning('renameNx', $duration, $params);
        }
        else
        {
            $this->logInfo('renameNx', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function type($key)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->type($key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);

        if(false === $result)
        {
            $this->warning('type', $duration, $params);
        }
        else
        {
            $this->logInfo('type', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function sort($key, $option = null)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->sort($key, $option);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'option' => $option);

        if(false === $result)
        {
            $this->warning('sort', $duration, $params);
        }
        else
        {
            $this->logInfo('sort', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function ttl($key)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->ttl($key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);

        if(false === $result)
        {
            $this->warning('ttl', $duration, $params);
        }
        else
        {
            $this->logInfo('ttl', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function restore($key, $ttl, $value)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->restore($key, $ttl, $value);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key
                        , 'ttl' => $ttl);

        if(false === $result)
        {
            $this->warning('restore', $duration, $params);
        }
        else
        {
            $this->logInfo('restore', $duration, $params);
        }

        return $result;
    }

    /**
     * LISTS
     * *************************************************************************************************
     */

    /**
     * @inheritdoc
     */
    public function blPop(array $keys)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->blPop($keys);
        $duration = $this->endMeasure($startTime);

        $params = array('keys' => $keys);

        if(false === $result)
        {
            $this->warning('blPop', $duration, $params);
        }
        else
        {
            $this->logInfo('blPop', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function brPop(array $keys)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->brPop($keys);
        $duration = $this->endMeasure($startTime);

        $params = array('keys' => $keys);

        if(false === $result)
        {
            $this->warning('brPop', $duration, $params);
        }
        else
        {
            $this->logInfo('brPop', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function brPoplPush($srcKey, $dstKey, $timeout)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->brPoplPush($srcKey, $dstKey, $timeout);
        $duration = $this->endMeasure($startTime);

        $params = array('srcKey' => $srcKey,
                        'dstKey' => $dstKey,
                        'timeout' => $timeout);

        if(false === $result)
        {
            $this->warning('brPoplPush', $duration, $params);
        }
        else
        {
            $this->logInfo('brPoplPush', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function lIndex($key, $index)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->lIndex($key, $index);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key
                        , 'index' => $index);

        if(false === $result)
        {
            $this->warning('lIndex', $duration, $params);
        }
        else
        {
            $this->logInfo('lIndex', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function lGet($key, $index)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->lGet($key, $index);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key
                        , 'index' => $index);

        if(false === $result)
        {
            $this->warning('lGet', $duration, $params);
        }
        else
        {
            $this->logInfo('lGet', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function lGetRange($key, $start, $end)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->lGetRange($key, $start, $end);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key,
                        'start' => $start,
                        'end' => $end);

        if(false === $result)
        {
            $this->warning('lGetRange', $duration, $params);
        }
        else
        {
            $this->logInfo('lGetRange', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function lInsert($key, $position, $pivot, $value)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->lInsert($key, $position, $pivot, $value);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key,
                        'position' => $position);

        if(-1 == $result)
        {
            $this->warning('lInsert', $duration, $params);
        }
        else
        {
            $this->logInfo('lInsert', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function lLen($key)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->lLen($key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);

        if(false === $result)
        {
            $this->warning('lLen', $duration, $params);
        }
        else
        {
            $this->logInfo('lLen', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function lPush($key, $value1, $value2 = null, $valueN = null)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->lPush($key, $value1, $value2, $valueN);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);

        if(false === $result)
        {
            $this->warning('lPush', $duration, $params);
        }
        else
        {
            $this->logInfo('lPush', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function lPushx($key, $value)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->lPushX($key, $value);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);

        if(false === $result)
        {
            $this->warning('lPushx', $duration, $params);
        }
        else
        {
            $this->logInfo('lPushx', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function lRange($key, $start, $end)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->lRange($key, $start, $end);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key,
                        'start' => $start,
                        'end' => $end);

        if(false === $result)
        {
            $this->warning('lRange', $duration, $params);
        }
        else
        {
            $this->logInfo('lRange', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function lRem($key, $value, $count)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->lRem($key, $value, $count);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'count' => $count);

        if(false === $result)
        {
            $this->warning('lRem', $duration, $params);
        }
        else
        {
            $this->logInfo('lRem', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function lRemove($key, $value, $count)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->lRemove($key, $value, $count);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'count' => $count);

        if(false === $result)
        {
            $this->warning('lRemove', $duration, $params);
        }
        else
        {
            $this->logInfo('lRemove', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function lSet($key, $index, $value)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->lSet($key, $index, $value);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'index' => $index);

        if(false === $result)
        {
            $this->warning('lSet', $duration, $params);
        }
        else
        {
            $this->logInfo('lSet', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function lSize($key)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->lSize($key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);

        if(false === $result)
        {
            $this->warning('lSize', $duration, $params);
        }
        else
        {
            $this->logInfo('lSize', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function lTrim($key, $start, $stop)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->lTrim($key, $start, $stop);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key,
                        'start' => $start,
                        'stop' => $stop);

        if(false === $result)
        {
            $this->warning('lTrim', $duration, $params);
        }
        else
        {
            $this->logInfo('lTrim', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function listTrim($key, $start, $stop)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->listTrim($key, $start, $stop);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key,
            'start' => $start,
            'stop' => $stop);

        if(false === $result)
        {
            $this->warning('listTrim', $duration, $params);
        }
        else
        {
            $this->logInfo('listTrim', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function rPop($key)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->rPop($key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);

        if(false === $result)
        {
            $this->warning('rPop', $duration, $params);
        }
        else
        {
            $this->logInfo('rPop', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function rPopLPush($srcKey, $dstKey)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->rPopLPush($srcKey, $dstKey);
        $duration = $this->endMeasure($startTime);

        $params = array('srcKey' => $srcKey, 'dstKey' => $dstKey);

        if(false === $result)
        {
            $this->warning('rPopLPush', $duration, $params);
        }
        else
        {
            $this->logInfo('rPopLPush', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function rPush($key, $value1, $value2 = null, $valueN = null)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->rPush($key, $value1, $value2, $valueN);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);

        if(false === $result)
        {
            $this->warning('rPush', $duration, $params);
        }
        else
        {
            $this->logInfo('rPush', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function rPushx($key, $value)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->rPushx($key, $value);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);

        if(false === $result)
        {
            $this->warning('rPushx', $duration, $params);
        }
        else
        {
            $this->logInfo('rPushx', $duration, $params);
        }

        return $result;
    }

    /**
     * SERVER
     * *************************************************************************************************
     */

    /**
     * @inheritdoc
     */
    public function bgrewriteaof()
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->bgrewriteaof();
        $duration = $this->endMeasure($startTime);

        $params = array();
        if(false !== $result)
        {
            $this->logInfo('bgrewriteaof', $duration, $params);
        }
        else
        {
            $this->error('bgrewriteaof', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function bgsave()
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->bgsave();
        $duration = $this->endMeasure($startTime);

        $params = array();
        if(false !== $result)
        {
            $this->logInfo('bgsafe', $duration, $params);
        }
        else
        {
            $this->error('bgsafe', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function config($operation, $key, $value = null)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->config($operation, $key, $value);
        $duration = $this->endMeasure($startTime);

        $params = array();
        if(false !== $result)
        {
            $this->logInfo('config', $duration, $params);
        }
        else
        {
            $this->error('config', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function dbSize(){
        $startTime = $this->startMeasure();
        $result = $this->redis->dbSize();
        $duration = $this->endMeasure($startTime);

        $params = array();
        if($result)
        {
            $this->logInfo('dbSize', $duration, $params);
        }
        else
        {
            $this->error('dbSize', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function flushAll()
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->flushAll();
        $duration = $this->endMeasure($startTime);

        $params = array();
        if($result)
        {
            $this->logInfo('flushAll', $duration, $params);
        }
        else
        {
            $this->error('flushAll', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function flushDB()
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->flushDB();
        $duration = $this->endMeasure($startTime);

        $params = array();
        if($result)
        {
            $this->logInfo('flushDB', $duration, $params);
        }
        else
        {
            $this->error('flushDB', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function info($option = null)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->logInfo($option);
        $duration = $this->endMeasure($startTime);

        $params = array('option' => $option);
        if($result)
        {
            $this->logInfo('info', $duration, $params);
        }
        else
        {
            $this->error('info', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function lastSave()
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->lastSave();
        $duration = $this->endMeasure($startTime);

        $params = array();
        if($result)
        {
            $this->logInfo('lastSave', $duration, $params);
        }
        else
        {
            $this->error('lastSave', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function resetStat()
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->resetStat();
        $duration = $this->endMeasure($startTime);

        $params = array();
        if($result)
        {
            $this->logInfo('resetStat', $duration, $params);
        }
        else
        {
            $this->error('resetStat', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->save();
        $duration = $this->endMeasure($startTime);

        $params = array();
        if($result)
        {
            $this->logInfo('save', $duration, $params);
        }
        else
        {
            $this->error('save', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function slaveof($host = '127.0.0.1', $port = 6379)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->slaveof($host, $port);
        $duration = $this->endMeasure($startTime);

        $params = array('host' => $host, 'port' => $port);
        if($result)
        {
            $this->logInfo('slaveof', $duration, $params);
        }
        else
        {
            $this->error('slaveof', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function time()
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->time();
        $duration = $this->endMeasure($startTime);

        $params = array();
        if($result)
        {
            $this->logInfo('time', $duration, $params);
        }
        else
        {
            $this->error('time', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function slowlog($operation, $length = 0)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->slowlog($operation, $length);
        $duration = $this->endMeasure($startTime);

        $params = array('operation' => $operation, 'length' => $length);
        if($result)
        {
            $this->logInfo('slowlog', $duration, $params);
        }
        else
        {
            $this->error('slowlog', $duration, $params);
        }

        return $result;
    }

    /**
     * SETS
     * *************************************************************************************************
     */

    /**
     * @inheritdoc
     */
    public function sAdd($key, $value1, $value2 = null, $valueN = null)
    {
        $startTime = $this->startMeasure();
        $values = func_get_args();
        $result = call_user_func_array(array($this->redis, 'sAdd'), $values);
        $duration = $this->endMeasure($startTime);

        $params = array();

        for ($i = 0; $i < func_num_args(); ++$i) {
            if ($i == 0) {
                $params['key'] = $values[$i];
            } else {
                $params['value' . $i] = $values[$i];
            }
        }

        if($result)
        {
            $this->logInfo('sAdd', $duration, $params);
        }
        else
        {
            $this->error('sAdd', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function sCard($key)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->sCard($key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);

        if(false !== $result)
        {
            $this->logInfo('sCard', $duration, $params);
        }
        else
        {
            $this->error('sCard', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function sContains($key, $value)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->sContains($key, $value);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);

        $this->logInfo('sInterStore', $duration, $params);

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function sDiff($key1, $key2, $keyN = null)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->sDiff($key1, $key2, $keyN);
        $duration = $this->endMeasure($startTime);

        $params = array('key1' => $key1, 'key2' => $key2);
        if(null !== $keyN) {
            $params['keyN'] = $keyN;
        }

        if($result)
        {
            $this->logInfo('sDiff', $duration, $params);
        }
        else
        {
            $this->error('sDiff', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function sDiffStore($dstKey, $key1, $key2, $keyN = null)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->sDiffStore($dstKey, $key1, $key2, $keyN);
        $duration = $this->endMeasure($startTime);

        $params = array('dstKey' => $dstKey, 'key1' => $key1, 'key2' => $key2);
        if(null !== $keyN) {
            $params['keyN'] = $keyN;
        }

        if($result)
        {
            $this->logInfo('sDiffStore', $duration, $params);
        }
        else
        {
            $this->error('sDiffStoreStore', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function sGetMembers($key)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->sGetMembers($key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);

        if(false !== $result)
        {
            $this->logInfo('sGetMembers', $duration, $params);
        }
        else
        {
            $this->error('sGetMembers', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function sInter($key1, $key2, $keyN = null)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->sInter($key1, $key2, $keyN);
        $duration = $this->endMeasure($startTime);

        $params = array('key1' => $key1, 'key2' => $key2);
        if(null !== $keyN) {
            $params['keyN'] = $keyN;
        }

        if($result)
        {
            $this->logInfo('sInter', $duration, $params);
        }
        else
        {
            $this->error('sInter', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function sInterStore($dstKey, $key1, $key2, $keyN = null)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->sInterStore($dstKey, $key1, $key2, $keyN);
        $duration = $this->endMeasure($startTime);

        $params = array('dstKey' => $dstKey, 'key1' => $key1, 'key2' => $key2);
        if(null !== $keyN) {
            $params['keyN'] = $keyN;
        }

        if($result)
        {
            $this->logInfo('sInterStore', $duration, $params);
        }
        else
        {
            $this->error('sInterStore', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function sIsMember($key, $value)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->sIsMember($key, $value);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);

        $this->logInfo('sIsMember', $duration, $params);

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function sMembers($key)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->sMembers($key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);

        if(false !== $result)
        {
            $this->logInfo('sMembers', $duration, $params);
        }
        else
        {
            $this->error('sMembers', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function sMove($srcKey, $dstKey, $member)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->sMove($srcKey, $dstKey, $member);
        $duration = $this->endMeasure($startTime);

        $params = array('srcKey' => $srcKey, 'dstKey' => $dstKey);
        if($result)
        {
            $this->logInfo('sMove', $duration, $params);
        }
        else
        {
            $this->error('sMove', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function sPop($key)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->sPop($key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);

        if(false !== $result)
        {
            $this->logInfo('sPop', $duration, $params);
        }
        else
        {
            $this->error('sPop', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function sRandMember($key)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->sRandMember($key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);

        if(false !== $result)
        {
            $this->logInfo('sRandMember', $duration, $params);
        }
        else
        {
            $this->error('sRandMember', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function sRem($key, $member1, $member2 = null, $memberN = null)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->sRem($key, $member1, $member2, $memberN);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);

        if(false !== $result)
        {
            $this->logInfo('sRem', $duration, $params);
        }
        else
        {
            $this->error('sRem', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function sRemove( $key, $member1, $member2 = null, $memberN = null )
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->sRemove($key, $member1, $member2, $memberN);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);

        if(false !== $result)
        {
            $this->logInfo('sRemove', $duration, $params);
        }
        else
        {
            $this->error('sRemove', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function sSize($key)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->sSize($key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);

        if(false !== $result)
        {
            $this->logInfo('sSize', $duration, $params);
        }
        else
        {
            $this->error('sSize', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function sUnion($key1, $key2, $keyN = null)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->sUnion($key1, $key2, $keyN);
        $duration = $this->endMeasure($startTime);

        $params = array('key1' => $key1, 'key2' => $key2);
        if(null !== $keyN) {
            $params['keyN'] = $keyN;
        }

        if(false !== $result)
        {
            $this->logInfo('sUnion', $duration, $params);
        }
        else
        {
            $this->error('sUnion', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function sUnionStore($dstKey, $key1, $key2, $keyN = null)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->sUnionStore($dstKey, $key1, $key2, $keyN);
        $duration = $this->endMeasure($startTime);

        $params = array('dstKey' => $dstKey, 'key1' => $key1, 'key2' => $key2);
        if(null !== $keyN) {
            $params['keyN'] = $keyN;
        }

        if(false !== $result)
        {
            $this->logInfo('sUnionStore', $duration, $params);
        }
        else
        {
            $this->error('sUnionStore', $duration, $params);
        }

        return $result;
    }

    /**
     * SORTED SETS
     * *************************************************************************************************
     */


    /**
     * @inheritdoc
     */
    public function zAdd($key, $score1, $value1, $score2 = null, $value2 = null, $scoreN = null, $valueN = null )
    {
        $startTime = $this->startMeasure();
        $result = call_user_func_array(array($this->redis, 'zAdd'), func_get_args());
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'score1' => $score1);
        if(null !== $score2) {
            $params['score2'] = $score2;
        }

        if(null !== $score2 && null !== $scoreN) {
            $params['scoreN'] = $scoreN;
        }

        if(false !== $result)
        {
            $this->logInfo('zAdd', $duration, $params);
        }
        else
        {
            $this->error('zAdd', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function zCard($key)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->zCard($key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);

        if(false !== $result)
        {
            $this->logInfo('zCard', $duration, $params);
        }
        else
        {
            $this->error('zCard', $duration, $params);
        }

        return $result;
    }

    /**
     * Returns the number of elements of the sorted set stored at the specified key which have
     * scores in the range [start,end]. Adding a parenthesis before start or end excludes it
     * from the range. +inf and -inf are also valid limits.
     *
     * @param   string  $key
     * @param   string  $start
     * @param   string  $end
     * @return  int     the size of a corresponding zRangeByScore.
     * @link    http://redis.io/commands/zcount
     * @example
     * <pre>
     * $redis->zAdd('key', 0, 'val0');
     * $redis->zAdd('key', 2, 'val2');
     * $redis->zAdd('key', 10, 'val10');
     * $redis->zCount('key', 0, 3); // 2, corresponding to array('val0', 'val2')
     * </pre>
     */
    public function zCount($key, $start, $end)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->zCount($key, $start, $end);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'start' => $start, 'end' => $end);

        if(false !== $result)
        {
            $this->logInfo('zCount', $duration, $params);
        }
        else
        {
            $this->error('zCount', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function zIncrBy($key, $value, $member)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->zIncrBy($key, $value, $member);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'value' => $value);

        if(false !== $result)
        {
            $this->logInfo('zIncrBy', $duration, $params);
        }
        else
        {
            $this->error('zIncrBy', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function zInter($Output, $ZSetKeys, array $Weights = null, $aggregateFunction = 'SUM')
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->zInter($Output, $ZSetKeys, $Weights, $aggregateFunction);
        $duration = $this->endMeasure($startTime);

        $params = array(
            'output' => $Output,
            'zSezKeys' => $ZSetKeys,
            'weights' => $Weights,
            'aggregationFunction' => $aggregateFunction);

        if(false !== $result)
        {
            $this->logInfo('zInter', $duration, $params);
        }
        else
        {
            $this->error('zInter', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function zRange($key, $start, $end, $withscores = null)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->zRange($key, $start, $end, $withscores);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'start' => $start, 'end' => $start, 'withscores' => $withscores);

        if(false !== $result)
        {
            $this->logInfo('zRange', $duration, $params);
        }
        else
        {
            $this->error('zRange', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function zRangeByScore($key, $start, $end, array $options = array())
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->zRangeByScore($key, $start, $end, $options);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'start' => $start, 'end' => $start);
        if(!empty($options)) {
            $params['options'] = $options;
        }

        if(false !== $result)
        {
            $this->logInfo('zRangeByScore', $duration, $params);
        }
        else
        {
            $this->error('zRangeByScore', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function zRevRangeByScore($key, $start, $end, array $options = array())
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->zRevRangeByScore($key, $start, $end, $options);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'start' => $start, 'end' => $start);
        if(!empty($options)) {
            $params['options'] = $options;
        }

        if(false !== $result)
        {
            $this->logInfo('zRevRangeByScore', $duration, $params);
        }
        else
        {
            $this->error('zRevRangeByScore', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function zRank($key, $member)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->zRank($key, $member);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);
        if(!empty($options)) {
            $params['options'] = $options;
        }

        if(false !== $result)
        {
            $this->logInfo('zRank', $duration, $params);
        }
        else
        {
            $this->error('zRank', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function zRem($key, $member1, $member2 = null, $memberN = null)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->zRem($key, $member1, $member2, $memberN);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'member1' => $member1);

        if(null !== $member2) {
            $params['member2'] = $member2;
        }

        if(null !== $memberN) {
            $params['memberN'] = $memberN;
        }

        if(false !== $result)
        {
            $this->logInfo('zRem', $duration, $params);
        }
        else
        {
            $this->error('zRem', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function zDelete($key, $member1, $member2 = null, $memberN = null)
    {
        $startTime = $this->startMeasure();
        $result = $$this->redis->zDelete($key, $member1, $member2, $memberN);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'member1' => $member1);

        if(null !== $member2) {
            $params['member2'] = $member2;
        }

        if(null !== $memberN) {
            $params['memberN'] = $memberN;
        }

        if(false !== $result)
        {
            $this->logInfo('zDelete', $duration, $params);
        }
        else
        {
            $this->error('zDelete', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function zRevRank($key, $member)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->zRevRank($key, $member);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);
        if(!empty($options)) {
            $params['options'] = $options;
        }

        if(false !== $result)
        {
            $this->logInfo('zRevRank', $duration, $params);
        }
        else
        {
            $this->error('zRevRank', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function zDeleteRangeByRank($key, $start, $end)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->zDeleteRangeByRank($key, $start, $end);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'start' => $start, 'end' => $end);

        if(false !== $result)
        {
            $this->logInfo('zDeleteRangeByRank', $duration, $params);
        }
        else
        {
            $this->error('zDeleteRangeByRank', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function zDeleteRangeByScore($key, $start, $end) {
        $startTime = $this->startMeasure();
        $result = $this->redis->zDeleteRangeByScore($key, $start, $end);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'start' => $start, 'end' => $end);

        if(false !== $result)
        {
            $this->logInfo('zDeleteRangeByScore', $duration, $params);
        }
        else
        {
            $this->error('zDeleteRangeByScore', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function zRemRangeByRank( $key, $start, $end )
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->zRemRangeByRank($key, $start, $end);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'start' => $start, 'end' => $end);

        if(false !== $result)
        {
            $this->logInfo('zRemRangeByRank', $duration, $params);
        }
        else
        {
            $this->error('zRemRangeByRank', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function zRemRangeByScore( $key, $start, $end )
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->zRemRangeByScore($key, $start, $end);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'start' => $start, 'end' => $end);

        if(false !== $result)
        {
            $this->logInfo('zRemRangeByScore', $duration, $params);
        }
        else
        {
            $this->error('zRemRangeByScore', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function zScore($key, $member)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->zScore($key, $member);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'member' => $member);

        if(false !== $result)
        {
            $this->logInfo('zScore', $duration, $params);
        }
        else
        {
            $this->error('zScore', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function zSize($key)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->zSize($key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);

        if(false !== $result)
        {
            $this->logInfo('zSize', $duration, $params);
        }
        else
        {
            $this->error('zSize', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function zUnion($Output, $ZSetKeys, array $Weights = null, $aggregateFunction = 'SUM')
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->zUnion($Output, $ZSetKeys, $Weights, $aggregateFunction);
        $duration = $this->endMeasure($startTime);

        $params = array(
            'Output' => $Output,
            'ZSetKeys' => $ZSetKeys,
            'Weights' => $Weights,
            'aggregateFunction' => $aggregateFunction
        );

        if(false !== $result)
        {
            $this->logInfo('zUnion', $duration, $params);
        }
        else
        {
            $this->error('zUnion', $duration, $params);
        }
    }

    /**
     * STRINGS
     * *************************************************************************************************
     */

    /**
     * @inheritdoc
     */
    public function append($key, $value)
    {
        $startTime = $this->startMeasure();
        $stringSize = $this->redis->append($key, $value);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'stringSize' => $stringSize);
        $this->logInfo('append', $duration, $params);

        return $stringSize;
    }

    /**
     * @inheritdoc
     */
    public function bitCount($key)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->bitCount($key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);
        $this->logInfo('bitCount', $duration, $params);

        return $result;
    }

//    /**
//     * @inheritdoc
//     */
//    public function bitOp($operation, $retKey, $key1, $key2, $key3 = null)
//    {
//        $startTime = $this->startMeasure();
//        $result = $this->redis->bitOp($operation, $retKey, $key1, $key2, $key3);
//        $duration = $this->endMeasure($startTime);
//
//        $params = array('operation' => $operation
//                        , 'retKey' => $retKey
//                        , 'key1' => $key1
//                        , 'key2' => $key2);
//
//        if(null !== $key3) {
//            $params['key3'] = $key3;
//        }
//
//        $this->info('bitOp', $duration, $params);
//
//        return $result;
//    }

    /**
     * @inheritdoc
     */
    public function decr($key)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->decr($key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);
        if(false === $result)
        {
            $this->warning('decr', $duration, $params);
        }
        else
        {
            $this->logInfo('decr', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        $startTime = $this->startMeasure();
        $value = $this->redis->get($key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);
        if(false === $value)
        {
            $this->warning('get', $duration, $params);
        }
        else
        {
            $this->logInfo('get', $duration, $params);
        }

        return $value;
    }

    /**
     * @inheritdoc
     */
    public function getBit($key, $offset)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->getBit($key, $offset);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key
                        , 'offset' => $offset);
        if(false === $result)
        {
            $this->warning('getBit', $duration, $params);
        }
        else
        {
            $this->logInfo('getBit', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function getRange($key, $start, $end)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->getRange($key, $start, $end);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key
                        , 'start' => $start
                        , 'end' => $end);
        if(false === $result)
        {
            $this->warning('getRange', $duration, $params);
        }
        else
        {
            $this->logInfo('getRange', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function getSet($key, $value)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->getSet($key, $value);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);
        if(false === $result)
        {
            $this->warning('getSet', $duration, $params);
        }
        else
        {
            $this->logInfo('getSet', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function incr($key)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->incr($key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);
        if(false === $result)
        {
            $this->warning('incr', $duration, $params);
        }
        else
        {
            $this->logInfo('incr', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function incrByFloat($key, $increment)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->incrByFloat($key, $increment);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key
                        , 'increment' => $increment);

        if(false === $result)
        {
            $this->warning('incrByFloat', $duration, $params);
        }
        else
        {
            $this->logInfo('incrByFLoat', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function mget(array $keys)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->mget($keys);
        $duration = $this->endMeasure($startTime);

        $params = array('keys' => $keys);
        if(false === $result)
        {
            $this->warning('mget', $duration, $params);
        }
        else
        {
            $this->logInfo('mget', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function mset(array $array)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->mset($array);
        $duration = $this->endMeasure($startTime);

        $params = array('keys' => array_keys($array));
        if(false === $result)
        {
            $this->warning('mset', $duration, $params);
        }
        else
        {
            $this->logInfo('mset', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value, $timeout = 0)
    {
        $startTime = $this->startMeasure();
        $success = $this->redis->set($key, $value, (int)$timeout);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'timeout' => (int)$timeout);
        if($success)
        {
            $this->logInfo('set', $duration, $params);
        }
        else
        {
            $this->error('set', $duration, $params);
        }


        return $success;
    }

    /**
     * @inheritdoc
     */
    public function setBit($key, $offset, $value)
    {
        $startTime = $this->startMeasure();
        $success = $this->redis->setBit($key, $value, $value);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key
                        , 'offset' => $offset);
        if($success)
        {
            $this->logInfo('setBit', $duration, $params);
        }
        else
        {
            $this->error('setBit', $duration, $params);
        }


        return $success;
    }

    /**
     * @inheritdoc
     */
    public function setex($key, $ttl, $value)
    {
        $startTime = $this->startMeasure();
        $success = $this->redis->setex($key, (int)$ttl, $value);
        $duration = $this->endMeasure($startTime);


        $params = array('key' => $key, 'ttl' => (int)$ttl);
        if($success)
        {
            $this->logInfo('setex', $duration, $params);
        }
        else
        {
            $this->error('setex', $duration, $params);
        }

        return $success;
    }

    /**
     * @inheritdoc
     */
    public function setnx($key, $value)
    {
        $startTime = $this->startMeasure();
        $success = $this->redis->setnx($key, $value);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);
        $params['notExistingKey'] = $success;

        $this->logInfo('setnx', $duration, $params);

        return $success;
    }

    /**
     * @inheritdoc
     */
    public function setRange($key, $offset, $value)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->setRange($key, $offset, $value);
        $duration = $this->endMeasure($startTime);


        $params = array('key' => $key, 'offset' => $offset);
        if($result)
        {
            $this->logInfo('setRange', $duration, $params);
        }
        else
        {
            $this->error('setRange', $duration, $params);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function strlen($key)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->strlen($key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);
        if($result)
        {
            $this->logInfo('strlen', $duration, $params);
        }
        else
        {
            $this->error('strlen', $duration, $params);
        }

        return $result;

    }

    /**
     * Return the position of the first bit set to 1 or 0 in a string. The position is returned, thinking of the
     * string as an array of bits from left to right, where the first byte's most significant bit is at position 0,
     * the second byte's most significant bit is at position 8, and so forth.
     * @param   string  $key
     * @param   int     $bit
     * @param   int     $start
     * @param   int     $end
     * @return  int     The command returns the position of the first bit set to 1 or 0 according to the request.
     *                  If we look for set bits (the bit argument is 1) and the string is empty or composed of just
     *                  zero bytes, -1 is returned. If we look for clear bits (the bit argument is 0) and the string
     *                  only contains bit set to 1, the function returns the first bit not part of the string on the
     *                  right. So if the string is three bytes set to the value 0xff the command BITPOS key 0 will
     *                  return 24, since up to bit 23 all the bits are 1. Basically, the function considers the right
     *                  of the string as padded with zeros if you look for clear bits and specify no range or the
     *                  start argument only. However, this behavior changes if you are looking for clear bits and
     *                  specify a range with both start and end. If no clear bit is found in the specified range, the
     *                  function returns -1 as the user specified a clear range and there are no 0 bits in that range.
     * @link    http://redis.io/commands/bitpos
     * @example
     * <pre>
     * $redis->set('key', '\xff\xff');
     * $redis->bitpos('key', 1); // int(0)
     * $redis->bitpos('key', 1, 1); // int(8)
     * $redis->bitpos('key', 1, 3); // int(-1)
     * $redis->bitpos('key', 0); // int(16)
     * $redis->bitpos('key', 0, 1); // int(16)
     * $redis->bitpos('key', 0, 1, 5); // int(-1)
     * </pre>
     */
    public function bitpos($key, $bit, $start = null, $end = null)
    {
        switch (func_num_args()) {
            case 2:
                $startTime = $this->startMeasure();
                $result = $this->redis->bitpos($key, $bit);
                $duration = $this->endMeasure($startTime);
                $params = array('key' => $key, 'bit' => $bit);
                break;
            case 3:
                $startTime = $this->startMeasure();
                $result = $this->redis->bitpos($key, $bit, $start);
                $duration = $this->endMeasure($startTime);
                $params = array('key' => $key, 'bit' => $bit, 'start' => $start);
                break;
            case 4:
                $startTime = $this->startMeasure();
                $result = $this->redis->bitpos($key, $bit, $start, $end);
                $duration = $this->endMeasure($startTime);
                $params = array('key' => $key, 'bit' => $bit, 'start' => $start, 'end' => $end);
                break;
            default:
                throw new \InvalidArgumentException();
        }

        if (false === $result) {
            $this->warning('bitpos', $duration, $params);
        } else {
            $this->logInfo('bitpos', $duration, $params);
        }
    }

    /**
     * Scan the keyspace for keys.
     * @param  int    $iterator Iterator, initialized to NULL.
     * @param  string $pattern  Pattern to match.
     * @param  int    $count    Count of keys per iteration (only a suggestion to Redis).
     * @return array            This function will return an array of keys or FALSE if there are no more keys.
     * @link   http://redis.io/commands/scan
     * @example
     * <pre>
     * $iterator = null;
     * while($keys = $redis->scan($iterator)) {
     *     foreach($keys as $key) {
     *         echo $key . PHP_EOL;
     *     }
     * }
     * </pre>
     */
    public function scan(&$iterator, $pattern = null, $count = null)
    {
        $params = array('cursor' => $iterator);

        if ($count !== null) {
            $startTime = $this->startMeasure();
            $result = $this->redis->scan($iterator, $pattern, $count);
            $duration = $this->endMeasure($startTime);
        } else {
            $startTime = $this->startMeasure();
            $result = $this->redis->scan($iterator, $pattern);
            $duration = $this->endMeasure($startTime);
        }

        if(null !== $pattern) {
            $params['pattern'] = $pattern;
        }

        if(null !== $pattern) {
            $params['count'] = $count;
        }

        if(false === $result) {
            $this->warning('scan', $duration, $params);
        } else {
            $this->logInfo('scan', $duration, $params);
        }

        return $result;
    }

    /**
     * Scan a HASH value for members, with an optional pattern and count.
     * @param   string    $key
     * @param   int       $iterator
     * @param   string    $pattern    Optional pattern to match against.
     * @param   int       $count      How many keys to return in a go (only a sugestion to Redis).
     * @return  array     An array of members that match our pattern.
     * @link    http://redis.io/commands/hscan
     * @example
     * <pre>
     * // $iterator = null;
     * // while($elements = $redis->hscan('hash', $iterator)) {
     * //     foreach($elements as $key => $value) {
     * //         echo $key . ' => ' . $value . PHP_EOL;
     * //     }
     * // }
     * </pre>
     */
    public function hScan($key, &$iterator, $pattern = null, $count = null)
    {
        $params = array('key' => $key, 'cursor' => $iterator);

        if ($count !== null) {
            $startTime = $this->startMeasure();
            $result = $this->redis->hScan($key, $iterator, $pattern, $count);
            $duration = $this->endMeasure($startTime);
        } else {
            $startTime = $this->startMeasure();
            $result = $this->redis->hScan($key, $iterator, $pattern);
            $duration = $this->endMeasure($startTime);
        }

        if(null !== $pattern) {
            $params['pattern'] = $pattern;
        }

        if(null !== $pattern) {
            $params['count'] = $count;
        }

        if(false === $result) {
            $this->warning('hScan', $duration, $params);
        } else {
            $this->logInfo('hScan', $duration, $params);
        }

        return $result;
    }

    /**
     * Scan a set for members.
     * @param   string  $key        The set to search.
     * @param   int     $iterator   LONG (reference) to the iterator as we go.
     * @param   null    $pattern    String, optional pattern to match against.
     * @param   int     $count      How many members to return at a time (Redis might return a different amount).
     * @return  array   PHPRedis will return an array of keys or FALSE when we're done iterating.
     * @link    http://redis.io/commands/sscan
     * @example
     * <pre>
     * $iterator = null;
     * while ($members = $redis->sscan('set', $iterator)) {
     *     foreach ($members as $member) {
     *         echo $member . PHP_EOL;
     *     }
     * }
     * </pre>
     */
    public function sScan($key, &$iterator, $pattern = null, $count = null)
    {
        $params = array('key' => $key, 'cursor' => $iterator);

        if ($count !== null) {
            $startTime = $this->startMeasure();
            $result = $this->redis->sScan($key, $iterator, $pattern, $count);
            $duration = $this->endMeasure($startTime);
        } else {
            $startTime = $this->startMeasure();
            $result = $this->redis->sScan($key, $iterator, $pattern);
            $duration = $this->endMeasure($startTime);
        }

        if(null !== $pattern) {
            $params['pattern'] = $pattern;
        }

        if(null !== $pattern) {
            $params['count'] = $count;
        }

        if(false === $result) {
            $this->warning('sScan', $duration, $params);
        } else {
            $this->logInfo('sScan', $duration, $params);
        }

        return $result;
    }

    /**
     * Scan a sorted set for members, with optional pattern and count.
     * @param   string  $key        String, the set to scan.
     * @param   int     $iterator   Long (reference), initialized to NULL.
     * @param   string  $pattern    String (optional), the pattern to match.
     * @param   int     $count      How many keys to return per iteration (Redis might return a different number).
     * @return  array   PHPRedis will return matching keys from Redis, or FALSE when iteration is complete.
     * @link    http://redis.io/commands/zscan
     * @example
     * <pre>
     * $iterator = null;
     * while ($members = $redis-zscan('zset', $iterator)) {
     *     foreach ($members as $member => $score) {
     *         echo $member . ' => ' . $score . PHP_EOL;
     *     }
     * }
     * </pre>
     */
    public function zScan($key, &$iterator, $pattern = null, $count = null)
    {
        $params = array('key' => $key, 'cursor' => $iterator);

        if ($count !== null) {
            $startTime = $this->startMeasure();
            $result = $this->redis->zScan($key, $iterator, $pattern, $count);
            $duration = $this->endMeasure($startTime);
        } else {
            $startTime = $this->startMeasure();
            $result = $this->redis->zScan($key, $iterator, $pattern);
            $duration = $this->endMeasure($startTime);
        }

        if(null !== $pattern) {
            $params['pattern'] = $pattern;
        }

        if(null !== $pattern) {
            $params['count'] = $count;
        }

        if(false === $result) {
            $this->warning('zScan', $duration, $params);
        } else {
            $this->logInfo('zScan', $duration, $params);
        }

        return $result;
    }

    /**
     * Blocks the current client until all the previous write commands are successfully transferred and
     * acknowledged by at least the specified number of slaves.
     * @param   int $numSlaves  Number of slaves that need to acknowledge previous write commands.
     * @param   int $timeout    Timeout in milliseconds.
     * @return  int The command returns the number of slaves reached by all the writes performed in the
     *              context of the current connection.
     * @link    http://redis.io/commands/wait
     * @example $redis->wait(2, 1000);
     */
    public function wait($numSlaves, $timeout)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->wait($numSlaves, $timeout);
        $duration = $this->endMeasure($startTime);

        $this->logInfo('wait', $duration, array('numSlaves' => $numSlaves, 'timeout' => $timeout));

        return $result;
    }

    /**
     * Adds all the element arguments to the HyperLogLog data structure stored at the key.
     * @param   string  $key
     * @param   array   $elements
     * @return  bool
     * @link    http://redis.io/commands/pfadd
     * @example $redis->pfAdd('key', array('elem1', 'elem2'))
     */
    public function pfAdd($key, array $elements)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->pfAdd($key, $elements);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key, 'elements' => $elements);

        if (false === $result) {
            $this->warning('pfAdd', $duration, $params);
        } else {
            $this->logInfo('pfAdd', $duration, $params);
        }

        return $result;
    }

    /**
     * When called with a single key, returns the approximated cardinality computed by the HyperLogLog data
     * structure stored at the specified variable, which is 0 if the variable does not exist.
     * @param   string|array    $key
     * @return  int
     * @link    http://redis.io/commands/pfcount
     * @example
     * <pre>
     * $redis->pfAdd('key1', array('elem1', 'elem2'));
     * $redis->pfAdd('key2', array('elem3', 'elem2'));
     * $redis->pfCount('key1'); // int(2)
     * $redis->pfCount(array('key1', 'key2')); // int(3)
     */
    public function pfCount($key)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->pfCount($key);
        $duration = $this->endMeasure($startTime);

        $params = array('key' => $key);

        $this->logInfo('pfCount', $duration, $params);

        return $result;
    }

    /**
     * Merge multiple HyperLogLog values into an unique value that will approximate the cardinality
     * of the union of the observed Sets of the source HyperLogLog structures.
     * @param   string  $destkey
     * @param   array   $sourcekeys
     * @return  bool
     * @link    http://redis.io/commands/pfmerge
     * @example
     * <pre>
     * $redis->pfAdd('key1', array('elem1', 'elem2'));
     * $redis->pfAdd('key2', array('elem3', 'elem2'));
     * $redis->pfMerge('key3', array('key1', 'key2'));
     * $redis->pfCount('key3'); // int(3)
     */
    public function pfMerge($destkey, array $sourcekeys)
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->pfMerge($destkey, $sourcekeys);
        $duration = $this->endMeasure($startTime);

        $params = array('destkey' => $destkey, 'sourcekeys' => $sourcekeys);

        $this->logInfo('pfMerge', $duration, $params);

        return $result;
    }

    /**
     * Returns a lexigraphical range of members in a sorted set, assuming the members have the same score. The
     * min and max values are required to start with '(' (exclusive), '[' (inclusive), or be exactly the values
     * '-' (negative inf) or '+' (positive inf).  The command must be called with either three *or* five
     * arguments or will return FALSE.
     * @param   string  $key    The ZSET you wish to run against.
     * @param   int     $min    The minimum alphanumeric value you wish to get.
     * @param   int     $max    The maximum alphanumeric value you wish to get.
     * @param   int     $offset Optional argument if you wish to start somewhere other than the first element.
     * @param   int     $limit  Optional argument if you wish to limit the number of elements returned.
     * @return  array   Array containing the values in the specified range.
     * @link    http://redis.io/commands/zrangebylex
     * @example
     * <pre>
     * foreach (array('a', 'b', 'c', 'd', 'e', 'f', 'g') as $char) {
     *     $redis->zAdd('key', $char);
     * }
     *
     * $redis->zRangeByLex('key', '-', '[c'); // array('a', 'b', 'c')
     * $redis->zRangeByLex('key', '-', '(c'); // array('a', 'b')
     * $redis->zRangeByLex('key', '-', '[c'); // array('b', 'c')
     * </pre>
     */
    public function zRangeByLex($key, $min, $max, $offset = null, $limit = null)
    {
        switch (func_num_args()) {
            case 3:
                $startTime = $this->startMeasure();
                $result = $this->redis->zRangeByLex($key, $min, $max);
                $duration = $this->endMeasure($startTime);
                $params = array(
                    'key' => $key,
                    'min' => $min,
                    'max' => $max
                );
                break;
            case 5:
                $startTime = $this->startMeasure();
                $result = $this->redis->zRangeByLex($key, $min, $max, $offset, $limit);
                $duration = $this->endMeasure($startTime);
                $params = array(
                    'key' => $key,
                    'min' => $min,
                    'max' => $max,
                    'offset' => $offset,
                    'limit' => $limit
                );
                break;
            default:
                throw new \InvalidArgumentException();
        }

        $this->logInfo('zRangeByLex', $duration, $params);

        return $result;
    }

    /**
     * @see zRangeByLex()
     * @param   string  $key
     * @param   int     $min
     * @param   int     $max
     * @param   int     $offset
     * @param   int     $limit
     * @return  array
     * @link    http://redis.io/commands/zrevrangebylex
     */
    public function zRevRangeByLex($key, $min, $max, $offset = null, $limit = null)
    {
        switch (func_num_args()) {
            case 3:
                $startTime = $this->startMeasure();
                $result = $this->redis->zRevRangeByLex($key, $min, $max);
                $duration = $this->endMeasure($startTime);
                $params = array(
                    'key' => $key,
                    'min' => $min,
                    'max' => $max
                );
                break;
            case 5:
                $startTime = $this->startMeasure();
                $result = $this->redis->zRevRangeByLex($key, $min, $max, $offset, $limit);
                $duration = $this->endMeasure($startTime);
                $params = array(
                    'key' => $key,
                    'min' => $min,
                    'max' => $max,
                    'offset' => $offset,
                    'limit' => $limit
                );
                break;
            default:
                throw new \InvalidArgumentException();
        }

        $this->logInfo('zRevRangeByLex', $duration, $params);

        return $result;
    }

    /**
     * Send arbitrary things to the redis server.
     * @param   string      $command    Required command to send to the server.
     * @param   mixed,...   $arguments  Optional variable amount of arguments to send to the server.
     * @return  mixed
     * @example
     * <pre>
     * $redis->rawCommand('SET', 'key', 'value'); // bool(true)
     * $redis->rawCommand('GET", 'key'); // string(5) "value"
     * </pre>
     */
    public function rawCommand($command)
    {
        $args = func_get_args();

        $startTime = $this->startMeasure();
        $result = call_user_func_array(array($this->redis, 'rawCommand'), $args);
        $duration = $this->endMeasure($startTime);

        $params = array();

        for ($i = 0; $i < func_num_args(); ++$i) {
            if ($i == 0) {
                $params['command'] = $args[$i];
            } else {
                $params['arg' . $i] = $args[$i];
            }
        }

        $this->logInfo('rawCommand', $duration, $params);

        return $result;
    }

    /**
     * Detect whether we're in ATOMIC/MULTI/PIPELINE mode.
     * @return  int     Either Redis::ATOMIC, Redis::MULTI or Redis::PIPELINE
     * @example $redis->getMode();
     */
    public function getMode()
    {
        $startTime = $this->startMeasure();
        $result = $this->redis->getMode();
        $duration = $this->endMeasure($startTime);

        $this->logInfo('getMode', $duration, array());

        return $result;
    }

    /**
     * Logger functionality.
     * ***************************************************************************************************************
     */

    /**
     * gets the collector/commands
     *
     * @return RedisDataCollectorInterface
     */
    public function getCommands()
    {
        return $this->collector;
    }


    /**
     * count executed commands
     *
     * @return int
     */
    public function countCommand()
    {
        return count($this->collector);
    }

    /**
     * add commands to the collector
     *
     * @param string $method
     * @param float $timeTaken
     * @param string $logType
     * @param array $params
     */
    private function collect($method, $timeTaken, $logType, array $params)
    {
        $this->collector->add(array(
            'cmd' => $method,
            'time_taken' => $timeTaken,
            'config' => $this->config,
            'log_type' => $logType,
            'params' => $params

        ));

    }

    /**
     * logs with info level
     *
     * @param string $method
     * @param float $timeTaken
     * @param array $params
     * @return string|void
     */
    private function logInfo($method, $timeTaken, array $params)
    {
        //add data to data collector
        $this->collect($method, $timeTaken, 'info', $params);

        //prepare for logger
        $host = $this->config['host'];
        $host.= (isset($this->config['port'])) ? ':' . $this->config['port'] : '' ;
        //log
        $this->logger->info('Command: "' . $method . '" Host: '.$host.' DB: "'.$this->config['db'] . '"', $params);
    }

    /**
     * logs with error level
     *
     * @param string $method
     * @param float $timeTaken
     * @param array $params
     */
    private function error($method, $timeTaken, array $params)
    {
        //add data to data collector
        $this->collect($method, $timeTaken, 'error', $params);

        //prepare for logger
        $host = $this->config['host'];
        $host.= (isset($this->config['port'])) ? ':' . $this->config['port'] : '' ;
        //log
        $this->logger->error('Command: "' . $method . '" Host: '.$host.' DB: "'.$this->config['db'] . '"', $params);
    }

    /**
     * logs with waring level
     *
     * @param string $method
     * @param float $timeTaken
     * @param array $params
     */
    private function warning($method, $timeTaken, array $params)
    {
        //add data to data collector
        $this->collect($method, $timeTaken, 'warning', $params);

        //prepare for logger
        $host = $this->config['host'];
        $host.= (isset($this->config['port'])) ? ':' . $this->config['port'] : '' ;
        //log
        $this->logger->warning('Command: "' . $method . '" Host: '.$host.' DB: "'.$this->config['db'] . '"', $params);
    }

    /**
     * returns a microtime
     *
     * @return float
     */
    private function startMeasure()
    {
        return microtime(true);
    }

    /**
     * returns a formatted time as float, calculated to ms
     *
     * @param float $timeStart
     * @return float
     */
    private function endMeasure($timeStart)
    {
        return (microtime(true) - $timeStart) * 1000;
    }
}
