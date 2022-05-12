<?php


class RedisClient
{
    private $redis;

    public function __construct($host = '127.0.0.1', $port = 6379)
    {
        $this->redis = new Redis();
        $this->redis->connect($host, $port);
    }

    public function setex($key, $time, $val)
    {
        return $this->redis->setex($key, $time, $val);
    }

    public function set($key, $val)
    {
        return $this->redis->set($key, $val);
    }

    public function get($key)
    {
        return $this->redis->get($key);
    }

    public function expire($key = null, $time = 0)
    {
        return $this->redis->expire($key, $time);
    }

    public function psubscribe($patterns = [], $callback)
    {
        $this->redis->psubscribe($patterns, $callback);
    }

    public function setOption()
    {
        $this->redis->setOption(\Redis::OPT_READ_TIMEOUT, -1);
    }
}