<?php

namespace app\modules\admin\controllers;

use app\models\Article;
use app\models\User;
use app\models\Comment;
use app\modules\admin\models\ArticleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Status;
use yii;

/**
 * ArticleGiiController implements the CRUD actions for Article model.
 */
class ArticleGiiController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Article models.
     *
     * @return string
     */
    public function actionIndex($user_id = null) 
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
    
        if ($user_id) {
            $user = User::findOne($user_id);
            $articleCount = Article::find()->where(['user_id' => $user_id])->count();
            $commentCount = Comment::find()->where(['user_id' => $user_id])->count();
            $rejectedArticlesCount = Article::find()->where(['user_id' => $user_id, 'status' => 'rejected'])->count();
            $deletedCommentsCount = Comment::find()->where(['user_id' => $user_id, 'status' => 'deleted'])->count();
        } else {
            $user = null;
            $articleCount = Article::find()->count();
            $commentCount = Comment::find()->count();
            $rejectedArticlesCount = 0;
            $deletedCommentsCount = 0;
        }
    
        $isBlocked = false;
        $threshold = 5; 
        if ($rejectedArticlesCount > $threshold || $deletedCommentsCount > $threshold) {
            $isBlocked = true;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user' => $user,
            'articleCount' => $articleCount,
            'commentCount' => $commentCount,
            'rejectedArticlesCount' => $rejectedArticlesCount,
            'deletedCommentsCount' => $deletedCommentsCount,
            'isBlocked' => $isBlocked,
        ]);
    }

    /**
     * Displays a single Article model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Article();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if (empty($model->user_id)) {
                    $model->user_id = 1; 
                }
                
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Статья успешно создана!');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
            $model->user_id = 1;
        }

        $users = User::find()->select(['id', 'login'])->indexBy('id')->column();

        return $this->render('create', [
            'model' => $model,
            'users' => $users,
        ]);
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Статья успешно обновлена!');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $users = User::find()->select(['id', 'login'])->indexBy('id')->column();

        return $this->render('update', [
            'model' => $model,
            'users' => $users,
        ]);
    }

    /**
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Статья успешно удалена!');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionPublish($id)
    {
        $model = $this->findModel($id);

        if ($model->status_id == 1 || $model->status_id == 3) { 
            $model->status_id = 2; 

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Статья опубликована');
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка при публикации статьи');
            }
        } else {
            Yii::$app->session->setFlash('warning', 'Статья не может быть опубликована, так как её статус не допускает эту операцию.');
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionReject(int $id) 
    {
        $model = $this->findModel($id);
        
        $model->scenario = Article::SCENARIO_REJECT; 
        
        if ($model->load(Yii::$app->request->post())) {
            $model->status_id = 4; 

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Статья отклонена. Причина: ' . $model->reject_reason);
                return $this->redirect(['update', 'id' => $model->id]); 
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка при отклонении статьи');
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
}