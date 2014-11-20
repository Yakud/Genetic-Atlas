<?php
namespace Lib\Storage;

use Elastica\Client;

/**
 *
 * @author yakud
 */
abstract class ElasticStorage {

    /**
     * Возвращает конфиг для создания клиента
     * @return array
     */
    protected function getClientConfig() {
        return array(
            'host' => '127.0.0.1',
            'port' => 9200
        );
    }

    /**
     * @var Client
     */
    protected $Client = null;

    /**
     * Возвращает клиент ElasticSearch
     * @return Client
     */
    public function getClient() {
        if (is_null($this->Client)) {
            $this->Client = new Client($this->getClientConfig());
        }

        return $this->Client;
    }
} 