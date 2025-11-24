<?php

namespace app\modules\account\controllers;

use Yii;
use app\models\Comment;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * CommentController implements the CRUD actions for Comment model.
 */
class CommentController extends Controller
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
                'access' => [
                    'class' => AccessControl::class,
                    'only' => ['delete'],
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['delete'],
                            'matchCallback' => function ($rule, $action) {
                                $id = Yii::$app->request->get('id');
                                if ($id) {
                                    $comment = Comment::findOne($id);
                                    return $comment && $comment->user_id === Yii::$app->user->id;
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
     * Creates a new Comment model.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Comment();
        $model->user_id = Yii::$app->user->id;

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Комментарий добавлен успешно!');
            return $this->redirect(['/account/article/view', 'id' => $model->article_id]);
        }

        Yii::$app->session->setFlash('error', 'Ошибка при добавлении комментария.');
        return $this->redirect(['/account/article/view', 'id' => $model->article_id]);
    }

    /**
     * Deletes an existing Comment model.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        
        $model = $this->findModel($id);
        $article_id = $model->article_id;
        $model->delete();

        Yii::$app->session->setFlash('success', 'Комментарий удален успешно!');
        return $this->redirect(['/account/article/view', 'id' => $article_id]);
    }

    /**
     * Finds the Comment model based on its primary key value.
     */
    protected function findModel($id)
    {
        if (($model = Comment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Страница не найдена.');
    }
}