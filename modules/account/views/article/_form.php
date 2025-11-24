<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Article $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="article-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'] 
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true])
        ->hint('Оставьте пустым для автоматической генерации из заголовка') ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'imageFile')->fileInput()
        ->hint('Поддерживаемые форматы: PNG, JPG, JPEG. Максимальный размер: 5MB') ?>

    <?php if (!$model->isNewRecord && $model->img): ?>
        <div class="form-group">
            <label>Текущее изображение:</label>
            <div>
                <?= Html::img($model->getImageUrl(), [
                    'style' => 'max-width: 300px; max-height: 200px;',
                    'class' => 'img-thumbnail'
                ]) ?>
            </div>
            <small class="text-muted">
                <?= Html::a('Удалить изображение', ['delete-image', 'id' => $model->id], [
                    'class' => 'text-danger',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить изображение?',
                        'method' => 'post',
                    ],
                ]) ?>
            </small>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>