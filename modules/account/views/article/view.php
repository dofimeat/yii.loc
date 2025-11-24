<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Article $model */
/** @var app\models\Comment $comment */
/** @var app\modules\account\models\CommentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $commentsDataProvider */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Мои статьи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="article-view">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить эту статью?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'img',
                        'format' => 'html',
                        'value' => function($model) {
                            if ($model->img) {
                                return Html::img($model->getImageUrl(), [
                                    'style' => 'max-width: 300px; max-height: 200px;',
                                    'class' => 'img-thumbnail'
                                ]);
                            }
                            return '<span class="text-muted">Нет изображения</span>';
                        },
                    ],
                    'title',
                    'content:ntext',
                    [
                        'attribute' => 'status_id',
                        'value' => function($model) {
                            return $model->status->name ?? 'Неизвестно';
                        },
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => 'datetime',
                    ],
                    [
                        'attribute' => 'reject_reason',
                        'visible' => !empty($model->reject_reason),
                    ],
                ],
            ]) ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-0">Комментарии (<?= $commentsDataProvider->totalCount ?>)</h3>
        </div>
        <div class="card-body">
            
            <?= ListView::widget([
                'dataProvider' => $commentsDataProvider,
                'itemView' => '_comment',
                'layout' => "{items}\n{pager}",
                'emptyText' => 'Пока нет комментариев.',
                'emptyTextOptions' => ['class' => 'text-muted text-center py-3'],
            ]) ?>
            
            <div class="mt-4">
                <h4>Добавить комментарий</h4>
                <?php $form = ActiveForm::begin([
                    'action' => Url::to(['view', 'id' => $model->id]),
                ]); ?>
                
                <?= $form->field($comment, 'content')->textarea([
                    'rows' => 3,
                    'placeholder' => 'Введите ваш комментарий...'
                ])->label(false) ?>
                
                <div class="form-group">
                    <?= Html::submitButton('Добавить комментарий', ['class' => 'btn btn-success']) ?>
                </div>
                
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

</div>