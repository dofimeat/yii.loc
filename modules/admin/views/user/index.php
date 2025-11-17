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
                            Статей: <?= $user->getArticlesCount() ?>
                        </p>
                        
                        <p class="card-text">
                            Комментариев: <?= $user->getCommentsCount() ?>
                        </p>

                        <div>
                            <?= Html::a('Просмотр', ['view', 'id' => $user->id], ['class' => 'btn btn-info']) ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>