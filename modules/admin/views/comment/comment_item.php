<?php

use yii\helpers\Html;

/** @var app\models\Comment $model */
?>

<div class="card" style="height: 100%;">
    <div class="card-body">
        <h2 class="card-title">
            <?= Html::encode($model->article->title) ?> - 
            <?= $model->user ? Html::encode($model->user->name) : 'Неизвестный пользователь' ?>
        </h2>
        
        <p class="card-subtitle mb-2 text-body-secondary">
            Дата создания: <b><?= Html::encode(Yii::$app->formatter->asDatetime($model->created_at)) ?></b>
        </p>
        
        <p class="card-text">
            <?= Html::encode(substr($model->content, 0, 100)) ?>
        </p>
        
        <p><strong>Статус:</strong> <?= $model->status ? Html::encode($model->status->name) : 'Неизвестный статус' ?></p>
        
        <div>
            <?= Html::a('Просмотр', ['view', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
            <?= Html::a('Опубликовать', ['publish', 'id' => $model->id], [
                'class' => 'btn btn-success', 
                'disabled' => $model->status_id == 2 ? true : false, 
            ]) ?>
        </div>
    </div>
</div>
