<?php

use Pheanstalk\Pheanstalk;

require_once 'Bean.php';

$bean = new Bean('192.168.7.206');

// 查看状态信息
//var_dump($bean->ph->stats());

// 显示目前存在的管道
//var_dump($bean->ph->listTubes());

// 向队列添加一个任务
$jobId = $bean->ph->useTube('testtube')->put(
    json_encode(['test' => time()]), // 任务数据
    Pheanstalk::DEFAULT_PRIORITY, // 优先级
    2, // 延迟时间，单位：秒
    60 // beanstalk将在60秒后重试作业
);
var_dump($jobId);

// 查看管道的信息
var_dump($bean->ph->statsTube('testtube'));

// 查看指定管道中某个任务的情况
/*$job = $bean->ph->watch('testtube')->reserve(); // 从管道中取出任务（消费者）
var_dump($job->getData()); // 获取任务数据
var_dump($bean->ph->delete($job)); // 从管道中删除任务*/

// 查看任务ID
//$job = $bean->ph->peek(1); // 直接取出任务ID为1的任务 [注:beanstalkd中所有任务的id都具有唯一性]

// 查看指定管道中的某一个任务情况
//var_dump($bean->ph->statsJob($job));

// --------- 生产 ----------
// put
/*$tube = $bean->ph->useTube('testtube2');
var_dump($tube->put('test'));*/

// --------- 消费 ----------
// watch 监听管道 [ 注: watch()同样可以监听多个管道 ]
/*$tube = $bean->ph->watch('testtube2');
var_dump($bean->ph->listTubesWatched()); // 打印监听的管道*/

// 监听多个管道
/*$tube = $bean->ph->watch('testtube')->watch('testtube2');
var_dump($bean->ph->listTubesWatched());*/

// ignore 监听管道，忽略default管道
//$tube = $bean->ph->watch('testtube')->ignore('default');

// reserve 监听管道,并且取出任务
/*$job = $bean->ph->watch('testtube')->reserve();
var_dump($bean->ph->delete($job)); // 从管道中删除任务*/

// 消费者例子
/*$bean->ph->watch('testtube');
while (true) {
    $job = $bean->ph->reserve();
    try {
        $jobPayload = $job->getData();
        var_dump($jobPayload);
        //sleep(1);

        $bean->ph->touch($job);
        $bean->ph->delete($job);
    } catch (Exception $e) {
        $bean->ph->release($job);
    }
}*/

// bury (预留) 将任务取出之后,发现后面执行的逻辑不成熟(比如发邮件,突然发现邮件服务器挂掉了)
// 或者说还不能执行后面的逻辑,需要把任务先封存起来,等待时机成熟了，再拿出这个任务进行消费
/*$job = $bean->ph->watch('testtube')->reserve(); // 取出任务
$bean->ph->bury($job); // 取出任务后，将任务放到一边(预留)*/

// peekBuried() 将处在bury状态的任务读取出来
/*$job = $bean->ph->peekBuried('testtube'); // 将管道中处在bury状态的任务读取出来
var_dump($bean->ph->statsJob($job)); // 打印任务状态(此时任务状态应该是bury)*/

// kickJob() 将处在bury任务状态的任务转化为ready状态
//$job = $bean->ph->peekBuried('testtube'); // 将管道中处在bury状态的任务读取出来
//$bean->ph->kickJob($job);

// kick() 将处在bury任务状态的任务转化为ready状态,有第二个参数int, 批量将任务id小于此数值的任务转化为ready
//$bean->ph->useTube('testtube')->kick(65); // 把管道中任务id小于65,并且任务状态处于bury的任务全部转化为ready

// peekReady() 将管道中处于ready状态的任务读出来
//$job = $ph->peekReady('testtube'); // 将管道中处于ready状态的任务读取出来
//var_dump($job);
//$ph->delete($job);

// peekDelay() 将管道中所有处于delay状态的任务读取出来
//$job = $bean->ph->peekDelayed('testtube');
//var_dump($job);
//$bean->ph->delete($job);

// pauseTube() 对整个管道进行延迟设置,让管道处于延迟状态
//$bean->ph->pauseTube('testtube', 10); // 设置管道延迟时间为10s
//$job = $bean->ph->watch('testtube')->reserve(); // 监听管道，并取出任务
//var_dump($job);

// resumeTube() 恢复管道，让管道处于不延迟状态，立即被消费
//$bean->ph->resumeTube('testtube'); // 取消管道NewUser的延迟状态，变为立即读取
//$job = $bean->ph->watch('testtube')->reserve(); // 监听NewUser管道，并取出任务
//var_dump($job);

// touch() 让任务重新计算任务超时重发ttr时间，相当于给任务延长寿命