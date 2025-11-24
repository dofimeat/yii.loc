<?php

use yii\helpers\Html;
use app\models\Comment;

/** @var app\models\Comment $model */
?>

<div class="comment-item border-bottom pb-3 mb-3">
    <div class="d-flex justify-content-between align-items-start mb-2">
        <div>
            <strong class="text-primary"><?= Html::encode($model->user->login ?? 'Пользователь') ?></strong>
            <small class="text-muted ml-2">
                <?= Yii::$app->formatter->asDatetime($model->created_at) ?>
            </small>
        </div>
        
        <?php if ($model->user_id == Yii::$app->user->id): ?>
            <?= Html::a('Удалить', 
                ['/account/comment/delete', 'id' => $model->id], 
                [
                    'class' => 'btn btn-outline-danger btn-sm',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить этот комментарий?',
                        'method' => 'post',
                    ],
                ]
            ) ?>
        <?php endif; ?>
    </div>
    
    <div class="comment-content">
        <?= nl2br(Html::encode($model->content)) ?>
    </div>
</div>