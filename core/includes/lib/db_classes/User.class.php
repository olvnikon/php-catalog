<?php

/**
 * @author Никонов Владимир Андреевич
 */
class User extends AbstractEntity
{

    public $id;
    public $email;
    public $password;
    public $nePasswd;
    public $type;
    public $phone;
    public $name;
    public $lastName;
    public $town;
    public $address;
    public $state;
    public $subscription;
    public $createDate;
    public $modifyDate;
    public $logonDate;
    public $priveleges = array();
    public $company;
    public $companyPhone;
    public $fax;
    public $postcode;

    /**
     *
     * @param int $page
     * @return boolean
     */
    public function hasAccessToPage($page)
    {
        if ($this->type == UTYPE_ADMIN) {
            return TRUE;
        }

        if (empty($this->priveleges) || !is_array($this->priveleges)) {
            return FALSE;
        }

        return in_array($page, $this->priveleges);
    }

    /**
     *
     * @return boolean
     */
    public function hasAccessToCms()
    {
        return $this->type == UTYPE_ADMIN || $this->type == UTYPE_MODERATOR;
    }

    /**
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->type == UTYPE_ADMIN;
    }

    /**
     *
     * @return boolean
     */
    public function isAdminOrModerator()
    {
        return $this->type == UTYPE_ADMIN
            || $this->type == UTYPE_MODERATOR;
    }

}

class UserManager extends AbstractManager
{

    public function getUserOrders(User $user)
    {
        $pm = new PurchaseManager();
        return $pm->getAll(
                'user_id=:user_id', array('user_id' => $user->id)
        );
    }

}
