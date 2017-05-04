<?php

/**
 * @author Никонов Владимир Андреевич
 */
class Cookie
{

    /**
     * Время жизни Cookie
     */
    const TIME = 9999999;

    /**
     * Получить переменную из Cookie
     *
     * @param string $var Имя переменной
     * @return mixed Значение переменной
     */
    public static function get($var)
    {
        return isset($_COOKIE[$var])
            ? $_COOKIE[$var]
            : '';
    }

    /**
     * Установить значение переменной Cookie
     *
     * @param string $var Переменная Cookie
     * @param mixed $value Значение переменной
     * @return void
     */
    public static function set($var, $value)
    {
        setcookie($var, $value, time() + self::TIME, '/');
    }

    /**
     * Проверяет наличие переменной в Cookie
     * @param string $var Имя переменной
     * @return boolean Пуста ли переменная
     */
    public static function isEmpty($var)
    {
        return empty($_COOKIE[$var]);
    }

    /**
     * Очистить Cookie
     * @param string $var Имя переменной
     * @return void
     */
    public static function emptyCookie($var)
    {
        setcookie($var, '', time() - self::TIME, '/');
        unset($_COOKIE[$var]);
    }

}
