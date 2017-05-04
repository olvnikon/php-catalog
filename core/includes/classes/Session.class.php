<?php

/**
 * @author Никонов Владимир Андреевич
 */
class Session
{

    /**
     * Стартовать сессию
     *
     * @return void
     */
    public static function start()
    {
        if (session_id()) {
            return;
        }
        session_start();
    }

    /**
     * Получить переменную из сессии
     *
     * @param string $var Имя переменной
     * @return mixed Значение переменной
     */
    public static function get($var)
    {
        return isset($_SESSION[$var])
            ? $_SESSION[$var]
            : '';
    }

    /**
     * Установить значение переменной в сессии
     *
     * @param string $var Переменная сессии
     * @param mixed $value Значение переменной
     * @return void
     */
    public static function set($var, $value)
    {
        $_SESSION[$var] = $value;
    }

    /**
     * Проверяет наличие переменной в сессии
     *
     * @param string $var Имя переменной
     * @return boolean Пуста ли переменная
     */
    public static function isEmpty($var)
    {
        return empty($_SESSION[$var]);
    }

    /**
     * Уничтожить сессию
     *
     * @return void
     */
    public static function destroy()
    {
        $_SESSION = array();
        session_destroy();
    }

}
