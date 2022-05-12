<?php
use Pheanstalk\Pheanstalk;

require_once 'Bean.php';

$bean = new Bean('192.168.7.206');

/*$jobId = $bean->ph->useTube('testtube')->put(
    json_encode(['test' => time()]), // 任务数据
    Pheanstalk::DEFAULT_PRIORITY, // 优先级
    7200, // 延迟时间，单位：秒
    60 // beanstalk将在60秒后重试作业
);
var_dump($jobId);*/

// 查看管道的信息
var_dump($bean->ph->statsTube('testtube'));

/*$job = $bean->ph->watch('testtube')->reserve();
var_dump($bean->ph->statsJob($job));
$bean->ph->delete($job);
die;*/

$job = $bean->ph->useTube('testtube')->peekDelayed();
var_dump($job);
$bean->ph->delete($job);
//var_dump($bean->ph->statsJob($job));

