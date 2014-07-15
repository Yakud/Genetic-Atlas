<?php
namespace Storage;

//use Redis\RedisClient;
use Predis\Client as RedisClient;

/**
 *
 * @author yakud
 */
abstract class RedisStorage {
    /**
     * @var RedisClient
     */
    protected $RedisClient = null;

    /**
     * @return array
     */
    protected function getStorageConfig() {
        return array(
            'scheme' => 'tcp',
            'host'   => '127.0.0.1',
            'port'   => 6379,
        );
    }

    /**
     * Возвращает клиент для Redis'а
     * @return RedisClient
     */
    public function getClient() {
        if (!is_null($this->RedisClient)) {
            return $this->RedisClient;
        }

        $config = $this->getStorageConfig();
        $this->RedisClient = new RedisClient($config);
        $this->RedisClient->connect();

        return $this->RedisClient;
    }
} 