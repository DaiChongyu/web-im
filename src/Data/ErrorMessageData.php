<?php


namespace Im\Data;

use Im\RedisConnManager;

/**
 * 消息发送失败队列
 * Class ErrorMessageData
 * @package Im\Data
 */
class ErrorMessageData
{
    /**
     * redis key
     * @return string
     */
    public static function key()
    {
        return 'im:errorMessage:solo';
    }

    /**
     * 消息存入失败list
     * @param $data
     * @return bool|int
     */
    public static function lPush($data)
    {
        $redis = RedisConnManager::getRedisConn();
        return $redis->lPush(self::key(), json_encode($data));
    }
}