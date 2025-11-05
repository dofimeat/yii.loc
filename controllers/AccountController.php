<?php

namespace app\controllers;

use app\models\Article;
use yii\web\Controller;
use app\models\ArticleForm;
use app\models\Comment;
use yii\web\NotFoundHttpException;
use Yii;

class AccountController extends Controller
{
    public $layout = 'story';

    public function actionMyArticles()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $articles = Article::find()->where(['user_id' => Yii::$app->user->id])->all();

        return $this->render('my-articles', ['articles' => $articles]);
    }
}
