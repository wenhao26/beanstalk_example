<?php

use Pheanstalk\Pheanstalk;

ini_set('display_errors', 'on');
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

/*
 * https://github.com/beanstalkd/beanstalkd
 *
 * 安装
 * 1、git clone https://github.com/beanstalkd/beanstalkd
 * 2、make
 *
 * 启动
 * 1、./beanstalkd
 * 2、./beanstalkd -l 127.0.0.1 -p 11301 -b /data/applogs/binstalkd/binlogs >/dev/null 2>&1 &
 *
 */

class Bean
{
    public $ph;

    public function __construct(string $host = '127.0.0.1', int $port = 11301)
    {
        $this->ph = Pheanstalk::create($host, $port);
    }

}

/*
生产者
useTube（） ： 如果没有管道，则创建对应管道，有，则直接使用
put（） ： 向管道中放任务

消费者：
watch（）：监听管道
reserve（）：将管道中处于ready状态的任务读取出来
可以使用delete 方法删除任务
可以使用release 方法将任务放回ready状态
可以使用bury 方法将任务先放一边（例如发邮件，邮箱服务器挂掉），等待条件成熟再取出来
 */

/*
 * // 查看有多少个tube
//var_export($pheanstalk->listTubes());

// 在 put 之前预申明要使用的管道，如果管道不存在，即创建
//$pheanstalk->useTube('test');

//设置要监听的tube
$pheanstalk->watch('test');

//取消对默认tube的监听，可以省略
$pheanstalk->ignore('default');

//查看监听的tube列表
var_export($pheanstalk->listTubesWatched());

//查看test的tube当前的状态
var_export($pheanstalk->statsTube('test'));
 */

/*
 * // put 任务 方式一; 返回新 job 的任务标识，整型值；
$pheanstalk->useTube('test')->put(
    'hello, beanstalk, i am job 1', // 任务内容
    23, // 任务的优先级, 默认为 1024
    0, // 不等待直接放到ready队列中.
    60 // 处理任务的时间(单位为秒)
);

// put 任务 方式二； 返回新 job 的任务标识，整型值；
$pheanstalk->putInTube(
    'test', // 管道名称
    'hello, beanstalk, i am job 2', // 任务内容
    23, // 任务的优先级, 默认为 1024
    0, // 不等待直接放到ready队列中. 如值为 60 表示 60秒；
    60 // 处理任务的时间(单位为秒)
);

// 给管道里所有新任务设置延迟
$pheanstalk->pauseTube('test', 30);

// 取消管道延迟
$pheanstalk->resumeTube('test');
 */

// Beanstalkd web端管理工具 https://github.com/mnapoli/phpBeanstalkdAdmin