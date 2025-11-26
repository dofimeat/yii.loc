<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;

/** @var app\models\Article $model */

// Обрезаем текст
$truncatedText = strip_tags($model->content);
if (mb_strlen($truncatedText) > 120) {
    $truncatedText = StringHelper::truncate($truncatedText, 120, '...');
}
?>

<div class="card h-100">
    <?php if ($model->img): ?>
        <div style="height: 200px; overflow: hidden;">
            <?= Html::img(
                '@web/img/' . $model->img, 
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

    <div class="card-body d-flex flex-column">
        <h5 class="card-title"><?= Html::encode($model->title) ?></h5>
        
        <p class="card-text flex-grow-1">
            <?= Html::encode($truncatedText) ?>
        </p>
        
        <div class="text-muted small mb-2">
            <div>
                <i class="far fa-user"></i>
                Автор: <?= Html::encode($model->author->name ?? 'Неизвестен') ?>
            </div>
            <div>
                <i class="far fa-calendar"></i>
                <?= Yii::$app->formatter->asDate($model->created_at) ?>
            </div>
            <?php if ($model->updated_at && $model->updated_at != $model->created_at): ?>
                <div>
                    <i class="far fa-edit"></i>
                    Обновлено: <?= Yii::$app->formatter->asDate($model->updated_at) ?>
                </div>
            <?php endif; ?>
            <div>
                <i class="far fa-comment"></i>
                Комментариев: <?= $model->getComments()->count() ?>
            </div>
        </div>
        
        <div class="mt-auto">
            <?= Html::a(
                'Подробнее', 
                ['view', 'id' => $model->id], 
                ['class' => 'btn btn-primary w-100']
            ) ?>
        </div>
    </div>
</div>