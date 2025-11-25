<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = 'Мой профиль';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="profile-index">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <?= Html::a('Изменить данные', ['update'], ['class' => 'btn btn-primary']) ?>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Основная информация</h5>
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'options' => ['class' => 'table table-striped table-bordered detail-view'],
                'attributes' => [
                    'login',
                    'email',
                    'name',
                    'phone',
                    'address',
                    [
                        'label' => 'Статус',
                        'value' => function($model) {
                            return $model->isBlocked() ? 
                                'Заблокирован' . ($model->ban_reason ? ' (Причина: ' . $model->ban_reason . ')' : '') : 
                                'Активен';
                        },
                    ],
                ],
            ]) ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Статистика и управление</h5>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-4">
                    <div class="border rounded p-3">
                        <h6>Статьи</h6>
                        <div class="h4 text-primary"><?= $model->getArticlesCount() ?></div>
                        <?= Html::a(
                            'Перейти к статьям', 
                            ['/account/article/index'], 
                            ['class' => 'btn btn-primary']
                        ) ?>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="border rounded p-3">
                        <h6>Комментарии</h6>
                        <div class="h4 text-info"><?= $model->getCommentsCount() ?></div>
                        <small class="text-muted">всего комментариев</small>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="border rounded p-3">
                        <h6>Безопасность</h6>
                        <div class="mb-2">
                            <?= Html::a(
                                'Сменить пароль', 
                                ['change-password'], 
                                ['class' => 'btn btn-warning']
                            ) ?>
                        </div>
                        <small class="text-muted">обновление пароля</small>
                    </div>
                </div>
            </div>
            
            <?php if ($model->isBlocked()): ?>
                <div class="alert alert-danger mt-4">
                    <strong>Внимание!</strong> Ваш аккаунт заблокирован.
                    <?php if ($model->ban_reason): ?>
                        <br>Причина: <?= Html::encode($model->ban_reason) ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>