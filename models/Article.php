<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class Article extends ActiveRecord
{
    public const SCENARIO_REJECT = 'reject';
    public $imageFile;

    public function rules()
    {
       return [
           [['status_id'], 'integer'], 
           [['title', 'content', 'user_id', 'name'], 'required'],
           [['content'], 'string'],
           [['user_id'], 'integer'],
           [['created_at', 'updated_at'], 'safe'],
           [['title'], 'string', 'max' => 255],
           
           [['imageFile'], 'file', 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 5], // 5MB
           
           ['reject_reason', 'required', 'on' => self::SCENARIO_REJECT],
       ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Автор',
            'title' => 'Заголовок',
            'name' => 'Название',
            'content' => 'Содержание',
            'img' => 'Изображение',
            'imageFile' => 'Загрузить изображение',
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
        if (empty($this->title) && !empty($this->name)) {
            $this->title = $this->name;
        }
        
        $this->imageFile = UploadedFile::getInstance($this, 'imageFile');
        if ($this->imageFile) {
            $basePath = Yii::getAlias('@webroot');
            $dir = $basePath . '/img/';
            
            if (!is_dir($dir)) {
                mkdir($dir, 0775, true);
            }
            
            $fileName = $this->generateUniqueFileName($this->imageFile->baseName, $this->imageFile->extension);
            $filePath = $dir . $fileName;
            
            if ($this->imageFile->saveAs($filePath)) {
                if (!$insert && $this->img && file_exists($basePath . $this->img)) {
                    unlink($basePath . $this->img);
                }
                $this->img = '/img/' . $fileName;
            }
        }
        
        if ($insert) {
            $this->created_at = date('Y-m-d H:i:s');
            if (empty($this->status_id)) {
                $this->status_id = 1; 
            }
        } else {
            $this->updated_at = date('Y-m-d H:i:s');
        }
        return true;
    }
    return false;
}

private function generateUniqueFileName($baseName, $extension)
{
    $dir = Yii::getAlias('@webroot') . '/img/';
    $fileName = $baseName . '.' . $extension;
    $counter = 1;
    
    while (file_exists($dir . $fileName)) {
        $fileName = $baseName . '_' . $counter . '.' . $extension;
        $counter++;
    }
    
    return $fileName;
}

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getStatus()
    {
        return $this->hasOne(Status::class, ['id' => 'status_id']);
    }

public function getImageUrl()
{
    if ($this->img) {
        $cleanPath = str_replace(['web/', 'img/', 'uploads/'], '', $this->img);
        
        if (file_exists('@web/img/' . $cleanPath)) {
            return '/@img1/' . $cleanPath;
        }
        if (file_exists('@web/uploads/' . $cleanPath)) {
            return '/uploads/' . $cleanPath;
        }
        if (file_exists($this->img)) {
            return '/' . $this->img;
        }
        return '@web/img/' . $cleanPath;
    }
    
    return '/img/no-image.jpg';
}

    public function getCommentsCount()
    {
        return $this->getComments()->count();
    }

    public function getComments()
    {
        return $this->hasMany(Comment::class, ['article_id' => 'id']);
    }
}