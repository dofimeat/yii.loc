<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Article $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="article-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                'method' => 'post',
            ],
            
        ]) ?>
        <?= Html::a('Отклонить', ['reject', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Опубликовать', ['publish', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'user_id',
                'value' => $model->user ? $model->user->name : 'Неизвестный автор', 
            ],
            'title',
            [
                'attribute' => 'content',
                'format' => 'ntext',
            ],
            [
                'attribute' => 'created_at',
            ],
            [
                'attribute' => 'updated_at', 
                // 'visible' => $model->updated_at != $model->created_at, 
            ],
            [
                'attribute' => 'status_id',
                'value' =>$model->status->name,
            ],
            [
                'label' => 'Причина отклонения',
                'value' => $model->reject_reason,
                'visible' => $model->status_id == 4,
            ],
            [
                'attribute' => 'img',
                'format' => 'raw',
                'value' => Html::img(Yii::getAlias('@web/img/') . $model->img, ['alt' => 'Изображение', 'style' => 'max-width:50%;']),
            ],
            
        ],
    ]) ?>

</div>
