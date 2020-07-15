<?php


namespace Im\Data;

use Im\RedisConnManager;

/**
 * 用户缓存
 * Class UserData
 * @package Im\Data
 */
class UserData
{

    /**
     * 用户数据集合
     * @var array
     */
    private $info = [];

    private $redis;

    /**
     * 初始化信息
     * UserData constructor.
     * @param $user
     */
    public function __construct($user)
    {
        $this->redis = RedisConnManager::getRedisConn();
        $this->info['name'] = $user;
        $this->info['avatar'] = '';
    }

    /**
     * redis-key
     * @return string
     */
    public function key()
    {
        return 'im:user:' . $this->info['name'];
    }

    /**
     * online在线
     * @return UserData
     */
    public function online()
    {
        $this->info['status'] = 'online';
        return $this;
    }

    /**
     * offline离线
     * @return $this
     */
    public function offline()
    {
        $this->info['status'] = 'offline';
        return $this;
    }

    /**
     * 设置消息Id
     * @param $fd
     * @return $this
     */
    public function setFd($fd)
    {
        $this->info['fd'] = $fd;
        return $this;
    }

    /**
     * 存储数据
     * @return bool
     */
    public function handle()
    {
        return $this->redis->hMSet($this->key(), $this->info);
    }

    /**
     * 通过用户名获取连接ID
     * @return string
     */
    public function getFd()
    {
        return $this->redis->hGet($this->key(), 'fd');
    }

    /**
     * 通过用户名获取连接id和状态
     * @return array
     */
    public function getUserFdAndStatus()
    {
        return $this->redis->hMGet($this->key(), array('fd', 'status'));
    }
}