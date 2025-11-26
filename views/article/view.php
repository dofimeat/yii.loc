<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Article $article */
/** @var app\models\Comment $newComment */
/** @var array $comments */

$this->title = $article->title;
$this->params['breadcrumbs'][] = ['label' => 'Все статьи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="article-view">

    <h1><?= Html::encode($article->title) ?></h1>

    <div class="card mb-4">
        <div class="card-body">
            <?php if ($article->img): ?>
                <div class="text-center mb-4">
                    <?= Html::img(
                        '@web/img/' . $article->img, 
                        [
                            'class' => 'img-fluid rounded',
                            'style' => 'max-height: 400px;',
                            'alt' => $article->title
                        ]
                    ) ?>
                </div>
            <?php endif; ?>

            <div class="article-content">
                <?= nl2br(Html::encode($article->content)) ?>
            </div>

            <div class="mt-4 pt-3 border-top">
                <?= DetailView::widget([
                    'model' => $article,
                    'attributes' => [
                        [
                            'label' => 'Автор',
                            'value' => $article->author->name ?? 'Неизвестен',
                        ],
                        [
                            'attribute' => 'created_at',
                            'format' => 'datetime',
                            'label' => 'Дата публикации',
                        ],
                        [
                            'attribute' => 'updated_at',
                            'format' => 'datetime',
                            'label' => 'Последнее обновление',
                            'visible' => $article->updated_at && $article->updated_at != $article->created_at,
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-0">
                Комментарии (<?= count($comments) ?>)
            </h3>
        </div>
        <div class="card-body">
            
            <?php if (!empty($comments)): ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment-item border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <strong class="text-primary">
                                    <?= Html::encode($comment->user->name ?? 'Пользователь') ?>
                                </strong>
                                <small class="text-muted ml-2">
                                    <?= Yii::$app->formatter->asDatetime($comment->created_at) ?>
                                </small>
                            </div>
                        </div>
                        
                        <div class="comment-content">
                            <?= nl2br(Html::encode($comment->content)) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted text-center py-3">Пока нет комментариев.</p>
            <?php endif; ?>
            
            <?php if (!Yii::$app->user->isGuest): ?>
                <div class="mt-4">
                    <h4>Добавить комментарий</h4>
                    <?php $form = ActiveForm::begin(); ?>
                    
                    <?= $form->field($newComment, 'content')->textarea([
                        'rows' => 3,
                        'placeholder' => 'Введите ваш комментарий...'
                    ])->label(false) ?>
                    
                    <div class="form-group">
                        <?= Html::submitButton('Добавить комментарий', ['class' => 'btn btn-success']) ?>
                    </div>
                    
                    <?php ActiveForm::end(); ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info mt-3">
                    Для добавления комментария - 
                    <a href="<?= Url::to(['/site/login']) ?>" class="alert-link">авторизуйтесь</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>