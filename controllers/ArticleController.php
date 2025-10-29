<?php

namespace app\controllers;

use app\models\Article;
use yii\web\Controller;
use app\models\ArticleForm;
use yii\web\NotFoundHttpException;
use Yii;

class ArticleController extends Controller
{
    private $articles;

    public $layout;

    public function actionIndex($id)
    {
        $article = $this->findModel($id); 

        $this->layout = 'story';
        return $this->render('index', [
            'article' => $article,
            'author' => $article->author,
        ]);
    }

    public function actionAll()
    {
        $articles = Article::find()->with('author')->all();
        $this->view->title = 'Все статьи';
        // var_dump($articles);
        // die;
        $this->layout = 'story';
        return $this->render('all', ['articles' => $articles]);
    }

public function actionAdd()
{
    $article = new Article();
    $this->layout = 'story';

    if ($article->load(Yii::$app->request->post())) {
        $article->user_id = Yii::$app->user->id; 
        
        if (empty($article->name)) {
            Yii::$app->session->setFlash('error', 'Поле "Название" обязательно для заполнения.');
            return $this->render('add', ['article' => $article]);
        }

        if ($article->validate() && $article->save()) {
            Yii::$app->session->setFlash('success', 'Статья успешно добавлена.');
            return $this->redirect(['article/all']);
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка при сохранении статьи: ' . print_r($article->getErrors(), true));
        }
    }

    return $this->render('add', ['article' => $article]);
}



    public function actionUpdate($id)
    {
        $article = $this->findModel($id); 
        $this->layout = 'story';

        if ($article->load(Yii::$app->request->post()) && $article->save()) {
            Yii::$app->session->setFlash('success', 'Статья успешно обновлена.');
            return $this->redirect(['view', 'id' => $article->id]); 
        }

        return $this->render('update', [
            'article' => $article,
        ]);
    }


    public function actionDelete($id)
    {
        $article = $this->findModel($id);

        if ($article->user_id !== Yii::$app->user->id) {
            throw new NotFoundHttpException('Статья не найдена.');
        }

        $article->delete(); 
        Yii::$app->session->setFlash('success', 'Статья успешно удалена'); 
        return $this->redirect(['all']); 
    }

    protected function findModel($id)
    {
        if (($model = Article::find()->with('author')->where(['id' => $id])->one()) !== null) {
            return $model; 
        }
        throw new NotFoundHttpException('Статья не найдена.');
    }

    public function actionMyArticles()
    {
        
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $articles = Article::find()->where(['user_id' => Yii::$app->user->id])->all();
        return $this->render('my-articles', ['articles' => $articles]);
    }
}
