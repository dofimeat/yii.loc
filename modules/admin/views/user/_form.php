<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\User $model */ 

$this->title = 'Блокировка пользователя: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Управление пользователями', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-block">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Введите причину блокировки пользователя: <?= Html::encode($model->name) ?></p>

    <div class="user-form">
        <?php $form = ActiveForm::begin(); ?>

        <h2>Причина блокировки:</h2>
        
        <?= $form->field($model, 'ban_reason')->textarea(['rows' => 6])->label('Введите вашу причину блокировки') ?>

        <div class="form-group">
            <?= Html::submitButton('Заблокировать', ['class' => 'btn btn-danger']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>