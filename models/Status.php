<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "status".
 *
 * @property int $id
 * @property string $name
 *
 * @property Article[] $articles
 */
class Status extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[Articles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Article::class, ['status_id' => 'id']);
    }

    public static function searchId($name)
    {
        $status = self::findOne(['name' => $name]);
        return $status ? $status->id : null; 
    }

        public static function getItemsList()
    {
        return (new Query())
            ->select(['name', 'id'])
            ->from(self::tableName())
            ->indexBy('id')
            ->column();
    }

    public static function getAllStatuses()
    {
        return self::find()
            ->select(['name', 'id'])
            ->indexBy('id')
            ->column();
    }

}
