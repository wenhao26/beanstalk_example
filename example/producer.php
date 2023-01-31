<?php

use Pheanstalk\Pheanstalk;

require_once 'Bean.php';

//$bean = new Bean('192.168.7.206');
$bean = new Bean('127.0.0.1');

// 查看状态信息
//var_dump($bean->ph->stats());
/*
 *    'current-jobs-urgent' => '0', // 优先级小于1024状态为ready的job数量
   'current-jobs-ready' => '0', // 状态为ready的job数量
   'current-jobs-reserved' => '0', // 状态为reserved的job数量
   'current-jobs-delayed' => '0', // 状态为delayed的job数量
   'current-jobs-buried' => '0', // 状态为buried的job数量
   'cmd-put' => '0', // 总共执行put指令的次数
   'cmd-peek' => '0', // 总共执行peek指令的次数
   'cmd-peek-ready' => '0', // 总共执行peek-ready指令的次数
   'cmd-peek-delayed' => '0', // 总共执行peek-delayed指令的次数
   'cmd-peek-buried' => '0', // 总共执行peek-buried指令的次数
   'cmd-reserve' => '0', // 总共执行reserve指令的次数
   'cmd-reserve-with-timeout' => '0',
   'cmd-delete' => '0',
   'cmd-release' => '0',
   'cmd-use' => '0', // 总共执行use指令的次数
   'cmd-watch' => '0', // 总共执行watch指令的次数
   'cmd-ignore' => '0',
   'cmd-bury' => '0',
   'cmd-kick' => '0',
   'cmd-touch' => '0',
   'cmd-stats' => '2',
   'cmd-stats-job' => '0',
   'cmd-stats-tube' => '0',
   'cmd-list-tubes' => '0',
   'cmd-list-tube-used' => '0',
   'cmd-list-tubes-watched' => '0',
   'cmd-pause-tube' => '0',
   'job-timeouts' => '0', // 所有超时的job的总共数量
   'total-jobs' => '0', // 创建的所有job数量
   'max-job-size' => '65535', // job的数据部分最大长度
   'current-tubes' => '1', // 当前存在的tube数量
   'current-connections' => '1', // 当前打开的连接数
   'current-producers' => '0', // 当前所有的打开的连接中至少执行一次put指令的连接数量
   'current-workers' => '0', // 当前所有的打开的连接中至少执行一次reserve指令的连接数量
   'current-waiting' => '0', // 当前所有的打开的连接中执行reserve指令但是未响应的连接数量
   'total-connections' => '2', // 总共处理的连接数
   'pid' => '3609', // 服务器进程的id
   'version' => '1.10', // 服务器版本号
   'rusage-utime' => '0.000000', // 进程总共占用的用户CPU时间
   'rusage-stime' => '0.001478', // 进程总共占用的系统CPU时间
   'uptime' => '12031', // 服务器进程运行的秒数
   'binlog-oldest-index' => '2', // 开始储存jobs的binlog索引号
   'binlog-current-index' => '2', // 当前储存jobs的binlog索引号
   'binlog-records-migrated' => '0',
   'binlog-records-written' => '0', // 累积写入的记录数
   'binlog-max-size' => '10485760', // binlog的最大容量
   'id' => '37604ac4305d3b16', // 一个随机字符串，在beanstalkd进程启动时产生
   'hostname' => 'localhost.localdomain',
 */

// 显示目前存在的管道
//var_dump($bean->ph->listTubes());

// 向队列添加一个任务
$delayTime = 2;
while (true) {
    if ($delayTime > 240) {
        break;
    }

    $jobId = $bean->ph->useTube('testtube')->put(
        json_encode(['test' => "延迟{$delayTime}秒执行"], JSON_UNESCAPED_UNICODE), // 任务数据
        Pheanstalk::DEFAULT_PRIORITY, // 优先级
        $delayTime, // 延迟时间，单位：秒
        60 // beanstalk将在60秒后重试作业
    );
    var_dump($jobId);
    $delayTime += 2;
    usleep(1000);
}
echo '任务执行完成...';
die;

/*$jobId = $bean->ph->useTube('testtube')->put(
    json_encode(['test' => time()]), // 任务数据
    Pheanstalk::DEFAULT_PRIORITY, // 优先级
    2, // 延迟时间，单位：秒
    60 // beanstalk将在60秒后重试作业
);
var_dump($jobId);*/

// 查看管道的信息
//var_dump($bean->ph->statsTube('testtube'));

// 查看指定管道中某个任务的情况
/*$job = $bean->ph->watch('testtube')->reserve(); // 从管道中取出任务（消费者）
var_dump($job->getData()); // 获取任务数据
var_dump($bean->ph->delete($job)); // 从管道中删除任务*/

// 查看任务ID
//$job = $bean->ph->peek(1); // 直接取出任务ID为1的任务 [注:beanstalkd中所有任务的id都具有唯一性]

// 查看指定管道中的某一个任务情况
//var_dump($bean->ph->statsJob($job));
/*
 *    'id' => '1', // job id
   'tube' => 'test', // job 所在的管道
   'state' => 'reserved', // job 当前的状态
   'pri' => '1024', // job 的优先级
   'age' => '5222', // 自 job 创建时间为止 单位：秒
   'delay' => '0',
   'ttr' => '60', // time to run
   'time-left' => '58', // 仅在job状态为reserved或者delayed时有意义，当job状态为reserved时表示剩余的超时时间
   'file' => '2', // 表示包含此job的binlog序号，如果没有开启它将为0
   'reserves' => '10', // 表示job被reserved的次数
   'timeouts' => '0', // 表示job处理的超时时间
   'releases' => '1', // 表示job被released的次数
   'buries' => '0', // 表示job被buried的次数
   'kicks' => '0', // 表示job被kiced的次数

 */


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