<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    const SCENARIO_BLOCK = 'block';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_CHANGE_PASSWORD = 'changePassword';
    
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCKED = 0;

    public $newPassword;
    public $passwordRepeat;
    public $currentPassword; 

    public static function tableName()
    {
        return 'user'; 
    }

    public function rules()
    {
        return [
            [['ban_reason'], 'string', 'max' => 255],
            ['ban_reason', 'required', 'on' => self::SCENARIO_BLOCK],
            ['ban_reason', 'string', 'max' => 255, 'on' => self::SCENARIO_BLOCK],
            
            [['name', 'phone', 'address'], 'string', 'max' => 255, 'on' => self::SCENARIO_UPDATE],
            
            [
                ['currentPassword', 'newPassword', 'passwordRepeat'], 
                'required', 
                'on' => self::SCENARIO_CHANGE_PASSWORD
            ],
            ['newPassword', 'string', 'min' => 6, 'on' => self::SCENARIO_CHANGE_PASSWORD],
            ['passwordRepeat', 'compare', 'compareAttribute' => 'newPassword', 'on' => self::SCENARIO_CHANGE_PASSWORD],
            [
                'currentPassword', 
                'validateCurrentPassword', 
                'on' => self::SCENARIO_CHANGE_PASSWORD
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Логин',
            'email' => 'Email',
            'name' => 'Имя',
            'phone' => 'Телефон',
            'address' => 'Адрес',
            'created_at' => 'Дата регистрации',
            'status_id' => 'Статус',
            'ban_reason' => 'Причина блокировки',
            'currentPassword' => 'Текущий пароль',
            'newPassword' => 'Новый пароль',
            'passwordRepeat' => 'Повторите новый пароль',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_UPDATE] = ['name', 'phone', 'address'];
        $scenarios[self::SCENARIO_CHANGE_PASSWORD] = ['currentPassword', 'newPassword', 'passwordRepeat'];
        return $scenarios;
    }


    public function validateCurrentPassword($attribute, $params)
    {
        if (!$this->validatePassword($this->currentPassword)) {
            $this->addError($attribute, 'Текущий пароль указан неверно.');
        }
    }


    public function setNewPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function isBlocked()
    {
        return $this->status_id === self::STATUS_BLOCKED;
    }

    public function getArticles()
    {
        return $this->hasMany(Article::class, ['user_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id); 
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null; 
    }

    public static function findByUsername($login)
    {
        return static::findOne(['login' => $login]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id; 
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key; 
    }

    /**
     * {@inheritdoc}
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey; 
    }

    public function validatePassword($password)
    {
        return password_verify($password, $this->password);
    }

    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getLogin() 
    {
        return $this->login; 
    }

    public function getArticlesCount()
    {
        return $this->hasMany(Article::class, ['user_id' => 'id'])->count();
    }

    public function getCommentsCount()
    {
        return $this->hasMany(Comment::class, ['user_id' => 'id'])->count();
    }

    public function getRejectedArticlesCount()
    {
        return Article::find()->where(['user_id' => $this->id, 'status_id' => 'rejected'])->count();
    }

    public function getDeletedCommentsCount()
    {
        return Comment::find()->where(['user_id' => $this->id, 'status_id' => 'deleted'])->count();
    }

    public function actionBlock()
    {
        $rejectedArticlesCount = $this->getRejectedArticlesCount();
        $deletedCommentsCount = $this->getDeletedCommentsCount();
        $threshold = 5; 
        
        return $rejectedArticlesCount > $threshold || $deletedCommentsCount > $threshold;
    }

    public function isAdmin()
    {
        return in_array($this->login, ['fog', 'fog']);
    }
}