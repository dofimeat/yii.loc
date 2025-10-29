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
            [['article_id', 'user_id', 'content'], 'required'],
            ['content', 'string'],
            [['article_id', 'user_id'], 'integer'],
            [['created_at'], 'safe']
        ];
    }

public function getArticle()
{
    return $this->hasOne(Article::class, ['id' => 'article_id']);
}

public function getUser()
{
    return $this->hasOne(User::class, ['id' => 'user_id']);
}

}
