<?php

/**
 * @author Никонов Владимир Андреевич
 */
class SecurityFuncs
{

    /**
     *
     * @var User Авторизованный пользователь
     */
    public $user;

    /**
     * Конструктор класса
     *
     * @return void
     */
    public function __construct()
    {
        Session::start();
        $this->user = Session::isEmpty('User')
            ? FALSE
            : unserialize(Session::get('User'));

        if (!$this->isLogged() && !$this->_tryAutorizeByCookie()) {
            Session::destroy();
            self::_emptyUserCookie();
        }
    }

    /**
     * Попытка авторизоваться, используя Cookie
     *
     * @return boolean Успех авторизации
     */
    private function _tryAutorizeByCookie()
    {
        return $this->autorize(
                Cookie::get('login'), Cookie::get('password'), TRUE
        );
    }

    /**
     * Авторизация пользователя
     *
     * @param string $email Email пользователя
     * @param string $passwd Пароль пользователя
     * @param boolean $setCookie Устанавливать ли Cookie
     * @return boolean Успех авторизации
     */
    public function autorize($email, $passwd, $setCookie = FALSE)
    {
        $um = new UserManager();
        $user = $um->getAll('email=:email AND password=:password AND state=1',
            array(':email' => $email, ':password' => md5($passwd)), 0, 1);
        if (empty($user)) {
            return FALSE;
        }

        $this->_updateSession($user[0]);
        $this->_updateLogon($um);
        $this->_updateCookie($setCookie);
        $this->_updateCart();
        return TRUE;
    }

    /**
     *
     * @param User $user
     * @return void
     */
    private function _updateSession(User $user)
    {
        Session::start();
        $this->user = $user;
        Session::set('User', serialize($this->user));
    }

    /**
     *
     * @param UserManager $um
     * @return void
     */
    private function _updateLogon(UserManager $um)
    {
        $this->user->logonDate = date('Y-m-d H:i:s');
        $um->update($this->user);
    }

    /**
     *
     * @param boolean $setCookie
     * @return void
     */
    private function _updateCookie($setCookie)
    {
        self::_emptyUserCookie();
        if ($setCookie) {
            $this->_setCookie();
        }
    }

    /**
     * Очищает все файлы Cookie
     *
     * @return void
     */
    private static function _emptyAllCookies()
    {
        self::_emptyUserCookie();
        Cookie::emptyCookie('cart_hash');
    }

    /**
     * Очищает файлы Cookie пользователя
     *
     * @return void
     */
    private static function _emptyUserCookie()
    {
        Cookie::emptyCookie('login');
        Cookie::emptyCookie('password');
    }

    /**
     * Устанавливает Cookie пользователя
     *
     * @return void
     */
    private function _setCookie()
    {
        Cookie::set('login', $this->user->email);
        Cookie::set('password', $this->user->nePasswd);
    }

    /**
     * @return void
     */
    private function _updateCart()
    {
        $cm = new CartManager();
        $userCart = $cm->getAll('user_id=:user_id',
            array('user_id' => $this->user->id));
        $guestCart = $cm->getAll('c_hash=:c_hash',
            array('c_hash' => Cookie::get('cart_hash')));
        if (!empty($userCart) && !empty($userCart[0]->products)) {
            Cookie::set('cart_hash', $userCart[0]->cHash);
            if (!empty($guestCart) && $guestCart[0]->id != $userCart[0]->id) {
                $cm->delete($guestCart[0]->id);
            }
        } elseif (empty($userCart) && !empty($guestCart)) {
            $this->_useGuestCart($guestCart[0], $cm);
        } elseif (!empty($userCart) && !empty($guestCart)
            && empty($userCart[0]->products) && !empty($guestCart[0]->products)) {
            $this->_useGuestCart($guestCart[0], $cm);
            $cm->delete($userCart[0]->id);
        }
    }

    /**
     *
     * @param Cart $cart
     * @param CartManager $cm
     * @return void
     */
    private function _useGuestCart(Cart $cart, CartManager $cm)
    {
        $cart->userId = $this->user->id;
        $cm->update($cart);
    }

    /**
     * Проверка того, что пользователь авторизован
     *
     * @return boolean Авторизован или нет
     */
    public function isLogged()
    {
        return !empty($this->user);
    }

    /**
     * Сделать выход из сессии
     *
     * @return void
     */
    public function logOut()
    {
        $this->user = FALSE;
        self::_emptyAllCookies();
        Session::set('User', FALSE);
        Session::destroy();
    }

}
