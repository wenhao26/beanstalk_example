<?php
/**
 * Class RedisUtils
 * @package app\APIs\service\extend
 */
class RedisUtils
{
    /**
     * Connection Host Address
     *
     * @var string
     */
    protected $host;

    /**
     * port
     *
     * @var int
     */
    protected $port;

    /**
     * ID of the selected storage database. Default is 0
     *
     * @var int
     */
    protected $dbId = 0;

    /**
     * The authentication code
     *
     * @var string
     */
    protected $auth;

    /**
     * Connection timeout, redis configuration file, default 30 seconds
     *
     * @var int
     */
    protected $timeout = 30;

    /**
     * RedisObject
     *
     * @var object
     */
    public $redis;

    /**
     * RedisUtils constructor.
     * @param array $option
     */
    public function __construct(array $option = [])
    {
        $this->host    = isset($option['host']) ? $option['host'] : '127.0.0.1';
        $this->port    = isset($option['port']) ? $option['port'] : 6379;
        $this->dbId    = isset($option['db_id']) ? $option['db_id'] : 0;
        $this->auth    = isset($option['auth']) ? $option['auth'] : '';
        $this->timeout = isset($option['timeout']) ? $option['timeout'] : $this->timeout;

        $this->redis = new \Redis();
        $this->redis->connect($this->host, $this->port, $this->timeout);
        if ($this->auth) {
            $this->redis->auth($this->auth);
        }
        $this->redis->select(intval($this->dbId));
    }

    /**
     * Set the value to KEY
     *
     * @param $key
     * @param $value
     * @param int $timeout
     * @return bool
     */
    public function set($key, $value, $timeout = null)
    {
        return $this->redis->set($key, $value, $timeout);
    }

    /**
     * Gets the value associated with the specified key value
     *
     * @param string $key
     * @return bool|mixed|string
     */
    public function get($key)
    {
        return $this->redis->get($key);
    }

    /**
     * Set a key-value with a life cycle
     *
     *
     * @param string $key
     * @param int $expire
     * @param string $value
     * @return bool
     */
    public function setEx($key, $expire, $value)
    {
        return $this->redis->setex($key, $expire, $value);
    }

    /**
     * Set a key-value with a life cycle
     * The unit of the period is milliseconds
     *
     * @param string $key
     * @param int $millisecond
     * @param string $value
     * @return bool
     */
    public function pSetEx($key, $millisecond, $value)
    {
        return $this->redis->setex($key, $millisecond, $value);
    }

    /**
     * Inserts one or more values to the end of the list
     *
     * @param string $key
     * @param mixed $data
     * @return bool|int
     */
    public function rPush($key, $data)
    {
        return $this->redis->rPush($key, $data);
    }

    /**
     * Removes and returns the first element of the list
     *
     * @param string $key
     * @return bool|mixed
     */
    public function lPop($key)
    {
        return $this->redis->lPop($key);
    }

    /**
     * Release subscription
     *
     * @param string $key
     * @param string|mixed $value
     * @return int
     */
    public function publish($key, $value)
    {
        return $this->redis->publish($key, $value);
    }

    /**
     * Remove the KEY
     *
     * @param string $key
     * @return int
     */
    public function del($key)
    {
        return $this->redis->del($key);
    }

    /**
     * Set hash key
     *
     * @param $h
     * @param $key
     * @param $value
     * @return bool|int
     */
    public function hSet($h, $key, $value)
    {
        return $this->redis->hSet($h, $key, $value);
    }

    /**
     * Set hash key
     *
     * @param $h
     * @param $key
     * @return string
     */
    public function hGet($h, $key)
    {
        return $this->redis->hGet($h, $key);
    }

    /**
     * Delete hash key
     *
     * @param $h
     * @param $key
     * @return bool|int
     */
    public function hDel($h, $key)
    {
        return $this->redis->hDel($h, $key);
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