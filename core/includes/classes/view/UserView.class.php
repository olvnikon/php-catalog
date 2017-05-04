<?php

class UserView
{

    /**
     *
     * @param Template $tpl
     * @param User $user
     * @return void
     */
    public static function processTpl(Template $tpl, User $user)
    {
        $tpl->setVar('User-Name', $user->name);
        $tpl->setVar('User-LastName', $user->lastName);
        $tpl->setVar('User-Email', $user->email);
        $tpl->setVar('User-Phone', $user->phone);
    }

}
