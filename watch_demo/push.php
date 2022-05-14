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


$timeout = 1;
$num = 1;
while (true) {
    /*if ($num > 10000) {
        echo 'End...';
        break;
    }*/
    //$redis->set('ex_key_' . $timeout, '12345678', $timeout);
    $redis->setEx("ex_key_{$num}", $timeout,'12345678');
    echo 'set timeout=' . $timeout . "\n";
    //$timeout += 1;
    $num++;
    usleep(100);
}



