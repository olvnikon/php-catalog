<?php

namespace Dictionary;

class UserType
{

    /**
     *
     * @return array
     */
    public static function getAll()
    {
        return array(
            UTYPE_ADMIN => 'Администратор',
            UTYPE_MODERATOR => 'Модератор',
            UTYPE_CLIENT => 'Пользователь'
        );
    }

}
