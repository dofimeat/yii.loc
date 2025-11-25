<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = 'Смена пароля';
$this->params['breadcrumbs'][] = ['label' => 'Мой профиль', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="profile-change-password">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="profile-form">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'currentPassword')->passwordInput([
            'maxlength' => true,
            'placeholder' => 'Введите текущий пароль'
        ]) ?>
        
        <?= $form->field($model, 'newPassword')->passwordInput([
            'maxlength' => true,
            'placeholder' => 'Введите новый пароль (минимум 6 символов)'
        ]) ?>
        
        <?= $form->field($model, 'passwordRepeat')->passwordInput([
            'maxlength' => true,
            'placeholder' => 'Повторите новый пароль'
        ]) ?>

        <div class="form-group">
            <?= Html::submitButton('Сменить пароль', ['class' => 'btn btn-warning']) ?>
            <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>