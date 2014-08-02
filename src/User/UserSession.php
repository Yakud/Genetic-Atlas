<?php
namespace User;
use User\Exception\UserNotFoundException;

/**
 *
 * @author akiselev
 */
class UserSession {
    /**
     * Возвращает юзера. Если тот авторизован, выставляет флаг.
     * Если не авторизован флаг не будет выставлен и юзер будет "пустой"
     * @return User
     */
    public static function getUser() {
        $User = new User();

        if (array_key_exists('user_id', $_SESSION)) {
            $userId = $_SESSION['user_id'];
            $User->setId($userId);

            try {
                $User->load()->setIsAuth(true);
            } catch (UserNotFoundException $Ex) {
                $User->setId(0)->isAuth(false);
            }
        }

        return $User;
    }

    /**
     * Авторизован ли юзер в данный момент
     * @return bool
     */
    public static function isAuth() {
        return self::getUser()->isAuth();
    }

    /**
     * Сохраняет авторизованного юзера в сессию
     * @param User $User
     */
    public static function saveAuthUser(User $User) {
        $_SESSION['user_id'] = $User->getId();
    }
} 