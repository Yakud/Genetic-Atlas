<?php
namespace User;

use Cache\CacheInterface;
use Cache\MemoryCache;
use Exception;
use Storage\MysqlStorage;
use Traits\Singleton;
use User\Exception\UserNotFoundException;

/**
 * Хранилище юзеров
 * @author akiselev
 */
class UserStorage extends MysqlStorage {
    use Singleton;

    /**
     * Кэш загруженных юзеров
     * @var CacheInterface
     */
    protected $UserCache = null;

    /**
     * Запрос на данные юзера по id
     */
    const QUERY_LOAD_BY_ID = "
        SELECT * FROM user WHERE id = :id LIMIT 1;
    ";

    /**
     * Запрос на выборку по email
     */
    const QUERY_LOAD_BY_EMAIL = "
        SELECT * FROM user WHERE email = :email LIMIT 1;
    ";

    /**
     * Запрос на добавление данных
     */
    const QUERY_INSERT = "
        INSERT INTO user (
            name,
            email,
            password
        ) VALUES (
            :name,
            :email,
            :password
        );
    ";

    /**
     * Запрос на обновление данных
     */
    const QUERY_UPDATE = "
        UPDATE
            user
        SET
            name = :name,
            email = :email,
            password = :password
        WHERE
            id = :id
    ";

    const QUERY_RANDOM = "
        SELECT *
        FROM `user`
        ORDER BY RAND( )
        LIMIT 5
    ";

    /**
     * Возвращает данные юзера по идентификатору
     * @param int $id
     * @throws UserNotFoundException
     * @return array
     */
    public function loadById($id) {
        $Cache = $this->getCache();
        if ($Cache->has($id)) {
            return $Cache->get($id);
        }

        $PDO = $this->getConnection();
        $Statement = $PDO->prepare(self::QUERY_LOAD_BY_ID);
        $Statement->execute(array(
            ':id' => $id,
        ));

        if (!$Statement->rowCount()) {
            throw new UserNotFoundException("User with id: {$id} not found");
        }

        $row = $Statement->fetch();
        $data = array();
        foreach ($row as $key => $value) {
            $data[$key] = $value;
        }

        $Cache->set($id, $data);

        return $data;
    }


    public function loadByEmail($email) {
        $PDO = $this->getConnection();
        $Statement = $PDO->prepare(self::QUERY_LOAD_BY_EMAIL);
        $Statement->execute(array(
            ":email" => $email,
        ));

        if (!$Statement->rowCount()) {
            throw new UserNotFoundException("User with email: {$email} not found");
        }

        $row = $Statement->fetch();
        $data = array();
        foreach ($row as $key => $value) {
            $data[$key] = $value;
        }

        return $data;
    }

    /**
     * Сохраняет данные юзера
     * @param int $id
     * @param array $userData
     * @return array
     * @throws UserNotFoundException
     */
    public function update($id, $userData) {
        $insertData = array(
            ":id"       => $id,
            ":name"     => null,
            ":email"    => null,
            ":password" => null,
        );

        foreach ($userData as $key => $value) {
            $key = ":{$key}";
            if (array_key_exists($key, $insertData)) {
                $insertData[$key] = trim(htmlspecialchars($value));
            }
        }

        $PDO       = $this->getConnection();
        $Statement = $PDO->prepare(self::QUERY_UPDATE);
        $Statement->execute($insertData);

        if (!$Statement->rowCount()) {
            throw new UserNotFoundException("User with id: {$id} not found");
        }
    }

    /**
     * Создает юзера в базе и возвращает id
     * @param array $userData
     * @throws Exception
     * @return int
     */
    public function insert($userData) {
        $insertData = array(
            ":name"     => null,
            ":email"    => null,
            ":password" => null,
        );

        foreach ($userData as $key => $value) {
            $key = ":{$key}";
            if (array_key_exists($key, $insertData)) {
                $insertData[$key] = trim(htmlspecialchars($value));
            }
        }

        $PDO = $this->getConnection();
        $Statement = $PDO->prepare(self::QUERY_INSERT);
        $Statement->execute($insertData);

        if (!$Statement->rowCount()) {
            $info = $Statement->errorInfo();
            throw new Exception($info[2], $info[1]);
        }

        return $PDO->lastInsertId();
    }

    /**
     * @return array
     */
    public function getRandomUsers() {
        $PDO = $this->getConnection();
        $Statement = $PDO->prepare(self::QUERY_RANDOM);
        $Statement->execute();

        return $Statement->fetchAll();
    }

    /**
     * Возвращает инстанс кэша
     * @return CacheInterface
     */
    protected function getCache() {
        if (!$this->UserCache) {
            $this->UserCache = new MemoryCache();
        }

        return $this->UserCache;
    }
}