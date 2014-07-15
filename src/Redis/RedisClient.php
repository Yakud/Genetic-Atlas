<?php
namespace Redis;

use Predis\Client;

/**
 *
 * @author yakud
 */
class RedisClient {
    /**
     * @var Client
     */
    protected $Client = null;

    /**
     * @param array $config
     */
    public function __construct(array $config = array()) {
        $this->Client = $this->createClient($config);
        $this->Client->connect();
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function set($key, $value) {
        return $this->Client->executeRaw(['SET', $key, $value]);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key) {
        return $this->Client->executeRaw(['GET', $key]);
    }

    /**
     * @param array $config
     * @return Client
     */
    protected function createClient(array $config) {
        return new Client($config);
    }
} 