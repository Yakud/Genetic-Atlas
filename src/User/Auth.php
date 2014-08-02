<?php
namespace User;
use User\Exception\UserNotFoundException;

/**
 * Класс для работы с авторизацией/регистрацией
 * @author akiselev
 */
class Auth {
    /**
     * Производит авторизацию. Если все успешно, то возвращает юзера, иначе null
     * Не записывает данные в сессию.
     *
     * @param string $email
     * @param string $password
     * @return User|null
     */
    public static function login($email, $password) {
        try {
            $User = UserFactory::getUserByEmail($email);
        } catch (UserNotFoundException $Ex) {
            return null;
        }

        if (password_verify($password, $User->getPassword())) {
            UserSession::saveAuthUser($User);
            return $User->setIsAuth(true);
        }

        return null;
    }

    /**
     * Производит регистрацию пользователя
     * @param string $email
     * @param string $password
     * @param string $name
     * @return User
     */
    public static function register($email, $password, $name) {
        $password = password_hash($password, PASSWORD_BCRYPT);

        $User = new User();
        $User->setEmail($email)
             ->setPassword($password)
             ->setName($name)
             ->save();

        UserSession::saveAuthUser($User);

        return $User;
    }
} 