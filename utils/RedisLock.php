<?php

/**
 * redis 锁
 */
class RedisLock
{
    private static $instance;

    private $redis_connect = '127.0.0.1';

    private $redis_port = '6379';

    private $redis_password = '';

    public $redis_instance = null;


    private function __construct()
    {
        $this->redisConnect();
    }

    private function __clone()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * 连接 redis
     *
     * @return $this
     */
    public function redisConnect()
    {
        if ($this->redis_instance) {
            return $this;
        }
        $redis = new Redis();
        $redis->connect($this->redis_connect, $this->redis_port);
        $redis->auth($this->redis_password);
        $this->redis_instance = $redis;

        return $this;
    }

    /**
     * @param string $connect
     * @param string $port
     * @param string $password
     *
     * @return $this
     */
    public function redis(string $connect = '127.0.0.1', string $port = '6379', string $password = '')
    {
        $this->redis_connect = $connect;
        $this->redis_port = $port;
        $this->redis_password = $password;

        return $this->redisConnect();
    }

    /**
     * 测试连接
     */
    public function redisTestConnect()
    {
        $this->redis_instance->set('test_redis_connect', 'redis ok');

        _dd($this->redis_instance->get('test_redis_connect'));
    }

    /**
     * redis 锁
     *
     * @param        $key
     * @param int $expire_date
     * @param int $count
     *
     * @return bool
     */
    public function redisLock($key, int $expire_date = 5, int $count = 1)
    {
        $key = md5($key);

        $result = $this->redis_instance->incr($key);
        if ($result <= $count) {
            $this->redis_instance->expire($key, $expire_date);

            return true;
        }

        return false;
    }

    /**
     * redis 解锁
     *
     * @param $key
     *
     * @return mixed
     */
    public function redisUnlock($key)
    {
        $key = md5($key);

        return $this->redis_instance->delete($key);
    }
}
