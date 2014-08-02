<?php
namespace User;

/**
 * Фабрика юзеров
 * @author akiselev
 */
class UserFactory {
    /**
     * Возвращает юзера по email
     * @param string $email
     * @return User
     */
    public static function getUserByEmail($email) {
        $Storage  = UserStorage::getInstance();
        $userData = $Storage->loadByEmail($email);

        $User = new User();
        $User->import($userData);

        return $User;
    }
} 