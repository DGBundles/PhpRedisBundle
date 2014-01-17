PhpRedisBundle
==============

Symfony 2 Bundle for phpredis extension

!!!WARNING
==========
This bundle is under construction. It is highly recommended not using this bundle for production


WORKING METHODS
===============
* hashes: hGet, hSet
* strings: append, bitCount, decr, get, getBit, getRange, getSet, incr, incrByFloat, mget, mset, set, setBit, setex, setnx, setRange, strlen
* keys: del, dump, exists, expire, expireAt, keys, migrate, move, object, persist, randomKey, rename, renameNx, type, sort, ttl, restore
* server: flushDB
* connection: close, select

Missing Integration Tests
=========================
* keys: sort (waiting for sadd function)

Methods Ignored
===============
* strings: delete, incrBy, decrBy, getMultiple, mSetNx, psetex
* keys: getKeys, setTimeout, pexpire, pexpireAt, renameKey, pttl

Methods not working:
=================
* strings: bitOp


Method Informations
===================
* set: parameter timeout is defined as float in docbloc and function. It is an int or long. Float will rase an error
* bitOf: always returns 0
* migrate: returns false when successful

Configuration
=============
here is a first sample configuration
**config.yml**
```
php_redis:
    clients:
        default:
            host: localhost
            port: ~
            db: 0
            pconnect: true
            logging: true
            connection_timeout: 1
        importstatus:
            host: localhost
            port: ~
            db: 1
            pconnect: true
            logging: true
```



Testing within Symfony2
=======================

for running the unit and integration tests add test parameters to your config:
**config_test.yml**
```
parameters:
  redis:
    host: localhost
    port: 6379
    db: 10
    db2: 9
```

Please keep in mind, that you have to run your own redis server.