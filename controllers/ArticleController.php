<?php

namespace app\controllers;

use app\models\Article;
use yii\web\Controller;
use app\models\ArticleForm;
use app\models\Comment;
use yii\web\NotFoundHttpException;
use Yii;

class ArticleController extends Controller
{
    private $articles;

    public $layout;

public function actionIndex($id)
{
    $article = $this->findModel($id);
    $comments = Comment::find()->where(['article_id' => $article->id])->all(); 

    $newComment = new Comment();
    
    if ($newComment->load(Yii::$app->request->post())) {
        $newComment->article_id = $article->id;
        $newComment->user_id = Yii::$app->user->id;

        if ($newComment->save()) {
            Yii::$app->session->setFlash('success', 'Комментарий добавлен.');
            return $this->redirect(['index', 'id' => $id]); 
        }
    }

    $this->layout = 'story';
    return $this->render('index', [
        'article' => $article,
        'author' => $article->author,
        'comments' => $comments, 
        'newComment' => $newComment, 
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
    if (Yii::$app->user->isGuest) {
        return $this->redirect(['site/login']);
    }

    $article = new Article();
    $this->layout = 'story';
    
    if ($article->load(Yii::$app->request->post())) {
        $article->user_id = Yii::$app->user->id;

        $imageFile = \yii\web\UploadedFile::getInstance($article, 'img');
        if ($imageFile) {
            $fileName = Yii::$app->security->generateRandomString(10) . '.' . $imageFile->extension;
            $article->img = $fileName;
        }
        
        if ($article->save()) {
            if ($imageFile) {
                $imagePath = Yii::getAlias('@webroot/img/');
                if (!file_exists($imagePath)) {
                    mkdir($imagePath, 0775, true);
                }
                $imageFile->saveAs($imagePath . $article->img);
            }
            
            Yii::$app->session->setFlash('success', 'Статья успешно добавлена!');
            return $this->redirect(['all']);
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка при сохранении статьи');
        }
    }

    return $this->render('add', ['article' => $article]);
}



public function actionUpdate($id)
{
    $article = $this->findModel($id);
    $this->layout = 'story';

    if ($article->user_id !== Yii::$app->user->id) {
        throw new NotFoundHttpException('Статья не найдена.');
    }
    $oldImage = $article->img; 
    if ($article->load(Yii::$app->request->post())) {
        $imageFile = \yii\web\UploadedFile::getInstance($article, 'img');

        if ($imageFile) {
            $fileName = Yii::$app->security->generateRandomString(10) . '.' . $imageFile->extension;
            $article->img = $fileName;
        } else {
            $article->img = $oldImage; 
        }

        if ($article->save()) {
            
            if ($imageFile) {
                $imagePath = Yii::getAlias('@webroot/img/');
                if (!file_exists($imagePath)) {
                    mkdir($imagePath, 0777, true);
                }
                
                $imageFile->saveAs($imagePath . $article->img);
                
                if ($oldImage && $oldImage != $article->img && file_exists($imagePath . $oldImage)) {
                    @unlink($imagePath . $oldImage);
                }
            }

            Yii::$app->session->setFlash('success', 'Статья успешно обновлена');
            return $this->redirect(['index', 'id' => $id]);
        }
    }

    return $this->render('update', ['article' => $article]);
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
