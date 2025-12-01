<?php

namespace app\controllers;

use app\models\Article;
use app\models\User;
use yii\web\Controller;
use app\models\ArticleForm;
use app\models\Comment;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;
use app\models\ArticleSearch;
use Yii;

class ArticleController extends Controller
{
    private $articles;

    public $layout;
// доделать
// public function behaviors()
// {
//     return [
//         'access' => [
//             'class' => AccessControl::class,
//             'ruleConfig' => [
//                 'class' => \yii\filters\AccessRule::class,
//             ],
//             'rules' => [
//                 [
//                     'allow' => true,
//                     'actions' => ['view', 'index'], 
//                     'roles' => ['?','@'], 
//                 ],
//                 [
//                     'allow' => false, 
//                     'actions' => ['add', 'update', 'delete'], 
//                 ],
//                 [
//                     'allow' => false, 
//                 ],
//             ],
//         ],
//     ];
// }

    public function actionIndex()
    {
        $searchModel = new ArticleSearch(); 
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel, 
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $article = $this->findModel($id);
        
        if ($article->status_id !== 2) {
            throw new NotFoundHttpException('Статья не найдена.');
        }
        
        $newComment = new Comment();
        
        if (Yii::$app->request->isPost && !Yii::$app->user->isGuest) {
            $newComment->article_id = $article->id;
            $newComment->user_id = Yii::$app->user->id;
            
            if ($newComment->load(Yii::$app->request->post()) && $newComment->save()) {
                Yii::$app->session->setFlash('success', 'Комментарий добавлен.');
                return $this->refresh();
            }
        }

        $comments = Comment::find()
            ->where(['article_id' => $article->id])
            ->with('user')
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('view', [
            'article' => $article,
            'author' => $article->author,
            'comments' => $comments,
            'newComment' => $newComment,
        ]);
    }

    public function actionAll()
    {
        // $articles = Article::find()->with('author')->all();
        // $this->view->title = 'Все статьи';
        // var_dump($articles);
        // die;
        // $this->layout = 'story';
        return $this->redirect(['index']);
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


}
