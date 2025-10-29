<?php

namespace app\models;

use yii\db\ActiveRecord;

class Article extends ActiveRecord
{
    public function rules()
    {
        return [
            [['title', 'content', 'user_id', 'name'], 'required'],
            [['content'], 'string'],
            [['user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            ['img', 'file', 'extensions' => ['png', 'jpg', 'jpeg']],
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

}