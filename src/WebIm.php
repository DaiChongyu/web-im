<?php


namespace Im;

use Swoole\WebSocket\Server;

/**
 * 基础服务
 * Class WebIm
 * @package Im
 */
class WebIm
{
    private $redis;

    public function __construct()
    {
        $this->redis = RedisConnManager::getRedisConn();
    }

    /**
     * 启动
     */
    public function run()
    {
        $server = new Server("0.0.0.0", 8877);
        $server->on('open', [$this, 'open']);
        $server->on('message', [$this, 'message']);
        $server->on('close', [$this, 'close']);
        $server->start();
    }

    /**
     * 连接
     * @param Server $server
     * @param $request
     */
    public function open(Server $server, $request)
    {
        $user = $request->get;
        $userName = $user['user'];
        if (!$userName) {
            $server->push($request->fd, "用户不存在");
            $server->close($request->fd);
        }
        $this->redis->set('im:user:' . $userName, $request->fd);
    }

    /**
     * 消息
     * @param Server $server
     * @param $request
     */
    public function message(Server $server, $request)
    {

    }

    /**
     * 关闭
     * @param Server $server
     * @param $request
     */
    public function close(Server $server, $request)
    {

    }

}