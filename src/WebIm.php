<?php


namespace Im;

use Im\Data\FdUserData;
use Im\Data\UserData;
use Swoole\WebSocket\Server;

/**
 * 基础服务
 * Class WebIm
 * @package Im
 */
class WebIm
{
    public function __construct()
    {
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
        // 设置用户信息
        (new UserData($userName))->setFd($request->fd)
            ->online()
            ->handle();
    }

    /**
     * 消息
     * @param Server $server
     * @param $request
     */
    public function message(Server $server, $request)
    {
        $sendJson = $request->data;
        if ($sendJson) {
            $sendData = json_decode($sendJson, true);
            switch ($sendData['type']) {
                // 一对一消息
                case 'solo':
                    (new Message())->sendSolo($server, $sendData['formUser'], $sendData['toUser'], $sendData['message']);
                    break;
            }
        }
    }

    /**
     * 关闭
     * @param Server $server
     * @param $request
     */
    public function close(Server $server, $request)
    {
        $user = (new FdUserData())->getUserByFd($request);
        if (!$user) return;
        // 下线用户
        (new UserData($user[0]))
            ->offline()
            ->handle();
    }

}