<?php

namespace app\modules\admin\controllers;

use yii\web\Controller;
use app\models\Article; 

class ArticleController extends Controller
{
    public function actionIndex()
    {
        $articles = Article::find()->all(); 
            
        return $this->render('index', [
            'articles' => $articles,
        ]);
    }

    public function actionView($id)
    {
        $article = Article::findOne($id);
        
        if ($article === null) {
            throw new \yii\web\NotFoundHttpException("Статья не найдена");
        }
        
        return $this->render('view', [
            'article' => $article,
        ]);
    }

    public function actionDelete($id)
    {
        $article = Article::findOne($id);
        
        if ($article !== null) {
            $article->delete();
        }
        
        return $this->redirect(['index']);
    }
}
