<?php

use yii\helpers\Html;

$this->title = 'Профиль пользователя';
?>

<div class="user-info">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Имя: <?= Html::encode($user->name) ?></p>
    <p>Логин: <?= Html::encode($user->login) ?></p>
    <p>Email:<?= Html::encode($user->email) ?></p>
    <p>Телефон: <?= Html::encode($user->phone) ?></p>
    <p>Адрес: <?= Html::encode($user->address) ?></p>
</div>
