<?php

namespace app\models;

use yii\base\Model;

class RegisterForm extends Model
{
    public $login;
    public $name;
    public $email;
    public $password;
    public $phone;
    public $address;
    public $birth;

    public function rules()
    {
        return [
            [['login', 'name', 'email', 'password', 'phone', 'birth'], 'required'],
            ['login', 'match', 'pattern' => '/^[a-z\d\-]+$/', 'message' => 'Только латиница, цифры, тире'],
            ['login', 'string', 'max' => 255],
            ['name', 'string', 'min' => 3, 'max' => 32],
            ['name', 'match', 'pattern' => '/^[а-яА-ЯёЁa-zA-Z\s]+$/u', 'message' => 'Имя может содержать только русские буквы'],
            ['email', 'email'],
            ['phone', 'match', 'pattern' => '/^\+?[\d\s]{10,15}$/', 'message' => 'Телефон должен быть в формате +1234567890 или 1234567890'],
            ['birth', 'integer', 'min' => 1900, 'max' => 2025],
            ['address', 'string', 'max' => 1000],
        ];
    }
}