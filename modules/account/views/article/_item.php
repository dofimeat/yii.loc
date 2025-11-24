<?php

use yii\helpers\Html;
use app\models\Article;

/** @var app\models\Article $model */

$truncatedText = strip_tags($model->content);
if (mb_strlen($truncatedText) > 120) {
    $truncatedText = mb_substr($truncatedText, 0, 120);
    $truncatedText = mb_substr($truncatedText, 0, mb_strrpos($truncatedText, ' '));
    $truncatedText .= '...';
}
?>

<div class="card mb-4" style="height: 100%;">
    <?php if ($model->img): ?>
        <div style="height: 200px; overflow: hidden;">
            <?= Html::img(
                $model->getImageUrl(), 
                [
                    'class' => 'card-img-top w-100 h-100', 
                    'style' => 'object-fit: cover;',
                    'alt' => $model->title
                ]
            ) ?>
        </div>
    <?php else: ?>
        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
            <span class="text-muted">Нет изображения</span>
        </div>
    <?php endif; ?>

    <div class="card-body">
        <h2 class="card-title"><?= Html::encode($model->title) ?></h2>
        
        <p class="card-subtitle mb-2 text-body-secondary">
            Дата создания: <b><?= Html::encode(Yii::$app->formatter->asDatetime($model->created_at)) ?></b>
        </p>
        
        <p class="card-text">
            <?= Html::encode($truncatedText) ?>
        </p>

        <p class="card-text">
            Статус: <?= Html::encode($model->status->name) ?>
        </p>

        <p class="card-text">
            Комментариев: <?= $model->getCommentsCount() ?>
        </p>

        <?php if ($model->updated_at && $model->updated_at != $model->created_at): ?>
            <p class="card-text">
                Обновлена: <?= Html::encode(Yii::$app->formatter->asDatetime($model->updated_at)) ?>
            </p>
        <?php endif; ?>

        <div>
            <?= Html::a('Подробнее', ['view', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>