<?php

namespace app\models;

use yii\db\ActiveRecord;

class Article extends ActiveRecord
{
    public function rules()
    {
       return [
           [['status_id'], 'integer'], 
           [['title', 'content', 'user_id', 'name'], 'required'],
           [['content'], 'string'],
           [['user_id'], 'integer'],
           [['created_at', 'updated_at'], 'safe'],
           [['title'], 'string', 'max' => 255],
           ['img', 'file', 'extensions' => ['png', 'jpg', 'jpeg']],
       ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Автор',
            'title' => 'Заголовок',
            'content' => 'Содержание',
            'img' => 'Изображение',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'status_id' => 'Статус',
            'reject_reason' => 'Причина отклонения',
        ];
    }

    public static function tableName()
    {
        return 'article'; 
    }

    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = date('Y-m-d H:i:s'); 
            } else {
                $this->updated_at = date('Y-m-d H:i:s'); 
            }
            return true;
        }
        return false;
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getStatus()
    {
        return $this->hasOne(Status::class, ['id' => 'status_id']);
    }
}