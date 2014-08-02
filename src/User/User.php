<?php
namespace User;

/**
 * Класс модели юзера
 * @author akiselev
 */
class User {
    /**
     * Идентификатор юзера
     * @var int
     */
    protected $id;

    /**
     * Имя пользователя
     * @var string
     */
    protected $name;

    /**
     * Email пользователя
     * @var string
     */
    protected $email;

    /**
     * Пароль пользователя (хэш)
     * @var string
     */
    protected $password;

    /**
     * Авторизован ли юзер
     * @var bool
     */
    protected $isAuth = false;

    /**
     * Если передан $id, то данные подгружаются из базы сразу
     * @param int $id
     */
    public function __construct($id = 0) {
        $this->setId($id);
        $this->load();
    }

    /**
     * Подгружает данные юзера из базы, если это возможно
     * @return $this
     */
    public function load() {
        $id = $this->getId();
        if (!$id) {
            return $this;
        }

        $Storage  = $this->getStorage();
        $userData = $Storage->loadById($id);
        $this->import($userData);

        return $this;
    }

    /**
     * Сохраняет данные юзера в хранилище
     * Обновляет данные уже имеющегося в хранилище юзера
     * Если в базе юзера нет, создает нового и устанавливает id в модель
     */
    public function save() {
        $Storage  = $this->getStorage();
        $userData = $this->export();

        $id = $this->getId();
        if (!$id) {
            $id = $Storage->insert($userData);
            $this->setId($id)->load();
        } else {
            $Storage->update($id, $userData);
        }
    }

    /**
     * Импортирует данные в модель
     * Ожидает массив ключ-значение: название поля => значение поля
     * @param array $data
     */
    public function import(array $data) {
        if (array_key_exists('id', $data)) {
            $this->setId($data['id']);
        }

        if (array_key_exists('name', $data)) {
            $this->setName($data['name']);
        }

        if (array_key_exists('password', $data)) {
            $this->setPassword($data['password']);
        }

        if (array_key_exists('email', $data)) {
            $this->setEmail($data['email']);
        }
    }

    /**
     * Экспортирует данные юзера
     * Возвращает массив ключ-значение: название поля => значение поля
     * @return array
     */
    public function export() {
        return array(
            'id'       => $this->getId(),
            'name'     => $this->getName(),
            'email'    => $this->getEmail(),
            'password' => $this->getPassword(),
        );
    }

    /**
     * Возвращает экземплят хранилища юзеров
     * @return UserStorage
     */
    protected function getStorage() {
        return UserStorage::getInstance();
    }

    /**
     * Возвращает идентификатор юзера
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Устанавливает идентификатор юзера
     * @param int $id
     * @return $this
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * Возвращает имя пользователя
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Устанавливает имя пользователя
     * @param string $name
     * @return $this
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Возвращает email
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Устанавливает email
     * @param string $email
     * @return $this
     */
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    /**
     * Возвращает хэш пароля
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Устанавливает хэш пароля
     * @param string $password
     * @return $this
     */
    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    /**
     * Возвращает true, если юзер авторизован
     * @return boolean
     */
    public function isAuth() {
        return $this->isAuth;
    }

    /**
     * Устанавливает флаг - авторизован ли юзер
     * @param boolean $isAuth
     * @return $this
     */
    public function setIsAuth($isAuth) {
        $this->isAuth = $isAuth;
        return $this;
    }
}