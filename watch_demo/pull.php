<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);

require_once '../utils/RedisUtils.php';
$conf = require_once '../config.php';

$redis = new RedisUtils([
    'host' => $conf['redis_conf']['host'],
    'port' => $conf['redis_conf']['port'],
    'auth' => $conf['redis_conf']['auth']
]);
$redis->setOption();
$redis->psubscribe(['__keyevent@0__:expired'], function ($redis, $pattern, $channel, $message) {
    echo "Pattern: $pattern\n";
    echo "Channel: $channel\n";
    echo "Payload: $message\n\n";

    // keyCallbackFunc为订阅事件后的回调函数，写业务处理逻辑部分，后续业务处理
});

/*function keyCallbackFunc($redis, $pattern, $chan, $msg)
{
    echo "Pattern: $pattern\n";
    echo "Channel: $chan\n";
    echo "Payload: $msg\n\n";

    // keyCallbackFunc为订阅事件后的回调函数，写业务处理逻辑部分，后续业务处理
}*/
