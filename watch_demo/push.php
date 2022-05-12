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


$timeout = 5;
while (true) {
    if ($timeout > 100) {
        echo 'End...';
        break;
    }
    $redis->set('ex_key_' . $timeout, '12345678', $timeout);
    echo 'set timeout=' . $timeout . "\n";
    $timeout += 5;
    sleep(1);
}



