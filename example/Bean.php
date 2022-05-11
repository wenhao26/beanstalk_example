<?php

use Pheanstalk\Pheanstalk;

ini_set('display_errors', 'on');
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

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

// Beanstalkd web端管理工具 https://github.com/mnapoli/phpBeanstalkdAdmin