<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Comment extends ActiveRecord
{
    public static function tableName()
    {
        return 'comments';
    }

    public function rules()
    {
        return [
            [['content', 'article_id', 'user_id'], 'required'],
            ['content', 'string'],
            [['article_id', 'user_id', 'status_id'], 'integer'],
            [['created_at'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_id' => 'Статья',
            'user_id' => 'Автор',
            'content' => 'Комментарий',
            'created_at' => 'Дата создания',
            'status_id' => 'Статус',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = date('Y-m-d H:i:s');
                if (empty($this->status_id)) {
                    $this->status_id = 1; 
                }
            }
            return true;
        }
        return false;
    }

    public function getArticle()
    {
        return $this->hasOne(Article::class, ['id' => 'article_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getStatus()
    {
        return $this->hasOne(CommentStatus::class, ['id' => 'status_id']);
    }

    public function getStatusName()
    {
        return $this->status->name;
    }
}