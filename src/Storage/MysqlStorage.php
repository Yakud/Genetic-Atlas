<?php
namespace Storage;
use Connection\ConnectionFacade;
use LogicException;
use PDO;
use PDOStatement;

/**
 * Класс Mysql хранилища
 * @author akiselev
 */
abstract class MysqlStorage {
    /**
     * Первичный ключ
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Возвращает соединение с MySQL
     * @return PDO
     */
    protected function getConnection() {
        return ConnectionFacade::getMysql();
    }

    /**
     * Возвращает первичный ключ
     * @return string
     */
    public function getPrimaryKey() {
        return $this->primaryKey;
    }

    /**
     * Устанавливает первичный ключ
     * @param string $primaryKey
     * @return $this
     */
    public function setPrimaryKey($primaryKey) {
        $this->primaryKey = $primaryKey;
        return $this;
    }
}