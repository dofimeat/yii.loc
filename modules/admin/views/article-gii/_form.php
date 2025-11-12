<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Article $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="article-form">

    <?php $form = ActiveForm::begin(); ?>

    <h2>Причина отклонения:</h2>
    
    <?= $form->field($model, 'reject_reason')->textarea(['rows' => 6])->label('Введите вашу причину отклонения') ?>

    <div class="form-group">
        <?= Html::submitButton('Отклонить', ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>