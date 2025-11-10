<?php

namespace app\models;

use yii\db\ActiveRecord;

class User extends ActiveRecord
{
    const ROLE_ADMIN = 'admin'; 
    const ROLE_USER = 'user'; 

    public $role;

    public function getIsAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }
}
