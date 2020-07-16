<?php


namespace Im\Data;

use Im\RedisConnManager;

/**
 * 连接id与用户id绑定关系
 * Class FdUserData
 * @package Im\Data
 */
class FdUserData
{
    /**
     * 用户标示
     * @var string
     */
    private $member;

    /**
     * 连接id
     * @var int
     */
    private $score;

    /**
     * redis
     * @var \Redis
     */
    private $redis;

    public function __construct()
    {
        $this->redis = RedisConnManager::getRedisConn();
    }

    /**
     * redis key
     * @return string
     */
    public function key()
    {
        return 'im:fd';
    }

    /**
     * 设置fd
     * @param $fd
     * @return FdUserData
     */
    public function setFd($fd)
    {
        $this->score = $fd;
        return $this;
    }

    /**
     * 设置用户
     * @param $member
     * @return $this
     */
    public function setMember($member)
    {
        $this->member = $member;
        return $this;
    }

    /**
     * 存储
     * @return int
     */
    public function handle()
    {
        return $this->redis->zAdd($this->key(), $this->score, $this->member);
    }

    /**
     * 通过fd获取用户标示
     * @param $fd
     * @return array
     */
    public function getUserByFd($fd)
    {
        return $this->redis->zRangeByScore($this->key(), $fd, $fd);
    }
}