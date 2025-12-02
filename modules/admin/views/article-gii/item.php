<?php

use yii\helpers\Html;

/** @var app\models\Article $model */
?>

<div class="card" style="height: 100%;">
    <div class="card-body">
        <h2 class="card-title"><?= Html::encode($model->title) ?></h2>
        <p class="card-subtitle mb-2 text-body-secondary">
            Дата создания: <b><?=Html::encode(Yii::$app->formatter->asDatetime($model->created_at))?></b>
        </p>
        <p class="card-subtitle mb-2 text-body-secondary">
            Автор:  <b><?= Html::encode($model->author->name)?></b>
        </p>
        <p class="card-text">
            <?= Html::encode($model->content) ?>
        </p>
        <?= Html::a('Читать далее', ['view', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </div>
</div>
