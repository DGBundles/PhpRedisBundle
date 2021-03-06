<?php
/**
 * Created by PhpStorm.
 * User: dawen
 * Date: 16.11.13
 * Time: 21:07
 */

namespace Dawen\Bundle\PhpRedisBundle\Tests\Component;

use Dawen\Bundle\PhpRedisBundle\Component\RedisClient;
use PHPUnit\Framework\TestCase;

class RedisClientKeysTest extends TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $redis = null;

    /** @var RedisClient */
    private $client = null;

    public function setUp()
    {
        if(!extension_loaded('redis'))
        {
            $this->markTestSkipped('no redis extension installed');
        }

        $this->redis = $this->getMockBuilder('Redis')
            ->disableOriginalConstructor()
            ->getMock();

        $this->client = new RedisClient($this->redis);

    }

    public function tearDown()
    {
        $this->redis = null;
        $this->client = null;
    }

    public function testInstance()
    {
        $this->assertInstanceOf('Dawen\Bundle\PhpRedisBundle\Component\RedisClientInterface', $this->client);
    }

    public function testDelAllParams()
    {
        $key1 = 'testkey1';
        $key2 = 'testkey2';
        $key3 = 'testkey3';

        $this->redis->expects($this->once())
            ->method('del')
            ->with( $this->equalTo($key1)
                    , $this->equalTo($key2)
                    , $this->equalTo($key3))
            ->will($this->returnValue(3));

        $result = $this->client->del($key1, $key2, $key3);

        $this->assertEquals(3, $result);
    }

    public function testDelArrayParam()
    {
        $keys = array('key1', 'key2', 'key3', 'key4');

        $this->redis->expects($this->once())
            ->method('del')
            ->with( $this->equalTo($keys))
            ->will($this->returnValue(4));

        $result = $this->client->del($keys);

        $this->assertEquals(4, $result);
    }

    public function testExists()
    {
        $key = 'testkey';

        $this->redis->expects($this->once())
            ->method('exists')
            ->with( $this->equalTo($key))
            ->will($this->returnValue(true));

        $result = $this->client->exists($key);

        $this->assertTrue($result);
    }

    public function testKeys()
    {
        $pattern = 'users.admin.*';
        $testValue = array('users.admin.myadmin', 'users.admin.TesterAdmin');

        $this->redis->expects($this->once())
            ->method('keys')
            ->with( $this->equalTo($pattern))
            ->will($this->returnValue($testValue));

        $result = $this->client->keys($pattern);

        $this->assertSame($testValue, $result);
    }

    public function testDump()
    {
        $key = 'testkey';
        $return = 'value';

        $this->redis->expects($this->once())
            ->method('dump')
            ->with($this->equalTo($key))
            ->will($this->returnValue($return));

        $result = $this->client->dump($key);

        $this->assertEquals($result, $result);
    }

    public function testExpire()
    {
        $key = 'testkey';
        $ttl = 1;

        $this->redis->expects($this->once())
            ->method('expire')
            ->with($this->equalTo($key)
                   , $this->equalTo($ttl))
            ->will($this->returnValue(true));

        $result = $this->client->expire($key, $ttl);

        $this->assertTrue($result);
    }

    public function testExpireAt()
    {
        $key = 'testkey';
        $ttl = time();

        $this->redis->expects($this->once())
            ->method('expireAt')
            ->with($this->equalTo($key)
                , $this->equalTo($ttl))
            ->will($this->returnValue(true));

        $result = $this->client->expireAt($key, $ttl);

        $this->assertTrue($result);
    }

    public function testMigrate()
    {
        $key = 'testkey';
        $ttl = time();
        $host = 'localhost';
        $port = 6789;
        $db = 9;

        $this->redis->expects($this->once())
            ->method('migrate')
            ->with($this->equalTo($host)
                   , $this->equalTo($port)
                   , $this->equalTo($key)
                   , $this->equalTo($db)
                   , $this->equalTo($ttl))
            ->will($this->returnValue(true));

        $result = $this->client->migrate($host, $port, $key, $db, $ttl);

        $this->assertTrue($result);
    }

    public function testMove()
    {
        $key = 'testkey';
        $db = 11;

        $this->redis->expects($this->once())
            ->method('move')
            ->with($this->equalTo($key)
                , $this->equalTo($db))
            ->will($this->returnValue(true));

        $result = $this->client->move($key, $db);

        $this->assertTrue($result);
    }

    public function testObject()
    {
        $type = 'refcount';
        $key = 'testkey';

        $this->redis->expects($this->once())
            ->method('object')
            ->with($this->equalTo($type)
                , $this->equalTo($key))
            ->will($this->returnValue(true));

        $result = $this->client->object($type, $key);

        $this->assertTrue($result);
    }

    public function testObjectException()
    {
        try
        {
            $this->client->object('asd', 'key');
        }
        catch(\InvalidArgumentException $exception)
        {
            $this->assertContains('string is not valid', $exception->getMessage());
            return true;
        }

        $this->assertTrue(false);
    }

    public function testPersist()
    {
        $key = 'testkey';

        $this->redis->expects($this->once())
            ->method('persist')
            ->with($this->equalTo($key))
            ->will($this->returnValue(true));

        $result = $this->client->persist($key);

        $this->assertTrue($result);
    }

    public function testRandomKey()
    {
        $key = 'testkey';

        $this->redis->expects($this->once())
            ->method('randomKey')
            ->will($this->returnValue($key));

        $result = $this->client->randomKey();

        $this->assertEquals($key, $result);
    }

    public function testRename()
    {
        $key = 'testkey';
        $dstKey = 'dstTestkey';

        $this->redis->expects($this->once())
            ->method('rename')
            ->with($this->equalTo($key)
                   , $this->equalTo($dstKey))
            ->will($this->returnValue(true));

        $result = $this->client->rename($key, $dstKey);

        $this->assertTrue($result);
    }

    public function testRenameNx()
    {
        $key = 'testkey';
        $dstKey = 'dstTestkey';

        $this->redis->expects($this->once())
            ->method('renameNx')
            ->with($this->equalTo($key)
                , $this->equalTo($dstKey))
            ->will($this->returnValue(true));

        $result = $this->client->renameNx($key, $dstKey);

        $this->assertTrue($result);
    }

    public function testType()
    {
        $key = 'testkey';

        $this->redis->expects($this->once())
            ->method('type')
            ->with($this->equalTo($key))
            ->will($this->returnValue(2));

        $result = $this->client->type($key);

        $this->assertEquals(2,$result);
    }

    public function testSort()
    {
        $key = 'testkey';
        $option = array('sort' => 'asc');

        $this->redis->expects($this->once())
            ->method('sort')
            ->with($this->equalTo($key)
                   , $this->equalTo($option))
            ->will($this->returnValue(2));

        $result = $this->client->sort($key, $option);

        $this->assertEquals(2,$result);
    }

    public function testTtl()
    {
        $key = 'testkey';

        $this->redis->expects($this->once())
            ->method('ttl')
            ->with($this->equalTo($key))
            ->will($this->returnValue(2));

        $result = $this->client->ttl($key);

        $this->assertEquals(2,$result);
    }

    public function testRestore()
    {
        $key = 'testkey';
        $ttl = 12;
        $value = 'testValue';

        $this->redis->expects($this->once())
            ->method('restore')
            ->with($this->equalTo($key)
                   , $this->equalTo($ttl)
                   , $this->equalTo($value))
            ->will($this->returnValue(true));

        $result = $this->client->restore($key, $ttl, $value);

        $this->assertTrue($result);
    }

    public function testScan()
    {
        $cursor = 'cursor';
        $pattern = 'pattern';
        $count = 'count';

        $value = array('one', 'two');

        $this->redis->expects($this->once())
            ->method('scan')
            ->with($this->identicalTo($cursor),
                $this->equalTo($pattern),
                $this->equalTo($count))
            ->will($this->returnValue($value));

        $result = $this->client->scan($cursor, $pattern, $count);

        $this->assertEquals($value, $result);
    }

    public function testPfAdd()
    {
        $key = 'key';
        $values = array('one', 'two');
        $value = 1;

        $this->redis->expects($this->once())
            ->method('pfAdd')
            ->with($this->equalTo($key),
                $this->equalTo($values))
            ->will($this->returnValue($value));

        $result = $this->client->pfAdd($key, $values);

        $this->assertEquals($value, $result);
    }

    public function testPfCount()
    {
        $key = 'key';
        $value = 42;

        $this->redis->expects($this->once())
            ->method('pfCount')
            ->with($this->equalTo($key))
            ->will($this->returnValue($value));

        $result = $this->client->pfCount($key);

        $this->assertEquals($value, $result);
    }

    public function testPfMerge()
    {
        $key = 'newkey';
        $logs = array('oldkey1', 'oldkey2');
        $value = true;

        $this->redis->expects($this->once())
            ->method('pfMerge')
            ->with($this->equalTo($key),
                $this->equalTo($logs))
            ->will($this->returnValue($value));

        $result = $this->client->pfMerge($key, $logs);

        $this->assertEquals($value, $result);
    }
}
