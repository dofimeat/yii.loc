<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\admin\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Управление пользователями';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <?php foreach ($dataProvider->models as $user): ?>
            <div class="col-md-4">
                <div class="card mb-4" style="height: 100%;">
                    <div class="card-body">
                        <h2 class="card-title"> <?= Html::encode($user->login) ?></h2>
                        
                        <p class="card-subtitle mb-2 text-body-secondary">
                            Email: <b><?= Html::encode($user->email) ?></b> 
                        </p>
                        
                        <p class="card-text">
                            Статус: <?= $user->status_id == 1 ? 'Активный' : 'Заблокированный' ?>
                        </p>
                        
                        <p class="card-text">
                            Статей: <?= Html::a($user->getArticlesCount(), ['article/index', 'user_id' => $user->id, 'ArticleSearch[status_id]' => 4]) ?>
                        </p>

                        <p class="card-text">
                            Комментариев: <?= Html::a($user->getCommentsCount(), ['comment/index', 'user_id' => $user->id]) ?>
                        </p>

                        <p class="card-text">
                            Отклоненные статьи: <?= Html::encode($user->getRejectedArticlesCount()) ?>
                        </p>

                        <p class="card-text">
                            Удаленные комментарии: <?= Html::encode($user->getDeletedCommentsCount()) ?>
                        </p>

                        <?php if ($user->isBlocked()): ?>
                            <div class="alert alert-danger">
                                Пользователь заблокирован.
                            </div>
                        <?php endif; ?>

                        <div>
                            <?= Html::a('Просмотр', ['view', 'id' => $user->id], ['class' => 'btn btn-info']) ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
