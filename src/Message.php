<?php

namespace Im;

use Im\Data\ErrorMessageData;
use Im\Data\UserData;
use Swoole\WebSocket\Server;

/**
 * 消息服务
 * Class Message
 * @package Im
 */
class Message
{
    /**
     * 单独给某人发消息
     * @param $server
     * @param $formUser
     * @param $toUser
     * @param $message
     */
    public function sendSolo(Server $server, $formUser, $toUser, $message)
    {
        // 获取接受消息用户的fd
        $toUserInfo = (new UserData($toUser))->getUserFdAndStatus();
        $sendData = [
            'formUser' => $formUser,
            'toUser' => $toUser,
            'message' => $message
        ];
        // 在线状态和fd是true的情况下才能发送
        if ($toUserInfo['fd'] && $toUserInfo['status'] == 'online') {
            $server->push($toUserInfo['fd'], json_encode($sendData));
        } else {
            ErrorMessageData::lPush($sendData);
        }
    }
}