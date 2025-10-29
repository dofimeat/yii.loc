<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

?>

<div class="col-md-12">
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('success') ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <h3>Данные регистрации:</h3>
        <ul class="list-group mb-3">
            <li class="list-group-item"><strong>Логин:</strong> <?= Html::encode($registeredData['login']) ?></li>
            <li class="list-group-item"><strong>Имя:</strong> <?= Html::encode($registeredData['name']) ?></li>
            <li class="list-group-item"><strong>Email:</strong> <?= Html::encode($registeredData['email']) ?></li>
            <li class="list-group-item"><strong>Телефон:</strong> <?= Html::encode($registeredData['phone']) ?></li>
            <li class="list-group-item"><strong>Адрес:</strong> <?= Html::encode($registeredData['address']) ?></li>
            <li class="list-group-item"><strong>Год рождения:</strong> <?= Html::encode($registeredData['birth']) ?></li>
        </ul>
    <?php else: ?>
        <h1>Регистрация</h1>
        
        <?php $form = ActiveForm::begin([
            'id' => 'register-form',
        ]); ?>

        <?= $form->field($model, 'login')->input('text', ['placeholder' => 'Введите логин'])->label('Логин') ?>
        <?= $form->field($model, 'name')->input('text', ['placeholder' => 'Введите имя'])->label('Имя') ?>
        <?= $form->field($model, 'email')->input('email', ['placeholder' => 'Введите email'])->label('Email') ?>
        <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Введите пароль'])->label('Пароль') ?>
        <?= $form->field($model, 'phone')->input('text', ['placeholder' => 'Введите телефон'])->label('Телефон') ?>
        <?= $form->field($model, 'address')->textarea(['rows' => 4, 'placeholder' => 'Введите адрес'])->label('Адрес') ?>
        <?= $form->field($model, 'birth')->input('number', ['min' => 1900, 'max' => date('Y'), 'placeholder' => 'Введите год рождения'])->label('Год рождения') ?>

        <div class="form-group">
            <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary']) ?> 
        </div>

        <?php ActiveForm::end(); ?>
    <?php endif; ?>
</div>
