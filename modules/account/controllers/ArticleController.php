<?php

namespace app\modules\account\controllers;

use app\models\Article;
use app\modules\account\models\ArticleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\account\models\CommentSearch;
use app\models\Comment;
use yii;
use yii\filters\AccessControl;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends Controller
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
                        'delete-image' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'only' => ['view', 'update', 'delete', 'delete-image'],
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['view', 'update', 'delete', 'delete-image'],
                            'matchCallback' => function ($rule, $action) {
                                $id = Yii::$app->request->get('id');
                                if ($id) {
                                    $article = Article::findOne($id);
                                    return $article && $article->user_id === Yii::$app->user->id;
                                }
                                return false;
                            }
                        ],
                    ],
                    'denyCallback' => function ($rule, $action) {
                        throw new NotFoundHttpException('У вас нет доступа к этой странице.');
                    }
                ],
            ]
        );
    }

    /**
     * Lists all Article models.
     *
     * @return string
     */
    public function actionIndex()
    {
        // Проверка блокировки пользователя
        if (Yii::$app->user->identity->isBlocked()) {
            Yii::$app->session->setFlash('error', 'Вы не можете создавать статьи, так как ваш аккаунт заблокирован.');
        }

        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Article model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        // Проверка доступа выполняется в behaviors через matchCallback
        // Дополнительная проверка не нужна
        
        $model = $this->findModel($id);
        $comment = new Comment();
        $comment->article_id = $id;
        
        $searchModel = new CommentSearch();
        $searchParams = ['CommentSearch' => ['article_id' => $id]];
        $commentsDataProvider = $searchModel->search($searchParams);
        
        return $this->render('view', [
            'model' => $model,
            'comment' => $comment,
            'commentsDataProvider' => $commentsDataProvider,
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (Yii::$app->user->identity->isBlocked()) {
            Yii::$app->session->setFlash('error', 'Вы не можете создавать статьи, так как ваш аккаунт заблокирован.');
            return $this->redirect(['index']);
        }

        $model = new Article();
        $model->user_id = Yii::$app->user->id;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if (empty($model->name) && !empty($model->title)) {
                    $model->name = $this->generateSlug($model->title);
                }
                
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Статья успешно создана!');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
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
     * 
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteImage($id)
    {
        
        $model = $this->findModel($id);
        
        if ($model->img) {
            $filePath = Yii::getAlias('@webroot') . $model->img;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        $model->img = null;
        if ($model->save(false)) { 
            Yii::$app->session->setFlash('success', 'Изображение удалено успешно!');
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка при удалении изображения.');
        }
        
        return $this->redirect(['update', 'id' => $id]);
    }

    /**
     *
     * @param string 
     * @return string
     */
    private function generateSlug($title)
    {
        $slug = preg_replace('/[^a-zA-Z0-9\s]/', '', $title);
        $slug = preg_replace('/\s+/', '-', $slug);
        $slug = strtolower($slug);
        $slug = trim($slug, '-');
        
        $baseSlug = $slug;
        $counter = 1;
        
        while (Article::find()->where(['name' => $slug])->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Страница не найдена.');
    }
}