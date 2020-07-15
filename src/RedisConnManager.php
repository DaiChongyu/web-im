<?php

namespace Im;

/**
 * 单例链接redis
 * Class RedisConnManager
 * @package app\common\library
 */
class RedisConnManager
{
    private static $redisInstance;

    private function __construct()
    {
    }

    static public function getRedisConn()
    {
        if (!self::$redisInstance instanceof self) {
            self::$redisInstance = new self;
        }
        $temp = self::$redisInstance;
        return $temp->connRedis();
    }

    static private function connRedis()
    {
        try {
            $redis = new \Redis();
            $redis->connect('127.0.0.1', 6379);
            $redis->auth(null);
            $redis->select(2);
            return $redis;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

}