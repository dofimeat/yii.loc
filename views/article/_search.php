<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ArticleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-search card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Поиск статей</h5>
    </div>
    <div class="card-body">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'options' => [
                'class' => 'row g-3 align-items-end',
            ],
        ]); ?>

        <div class="col-md-4">
            <?= $form->field($model, 'title', [
                'template' => '{label}{input}',
                'labelOptions' => ['class' => 'form-label']
            ])->textInput([
                'placeholder' => 'Введите заголовок',
                'class' => 'form-control'
            ])->label('Заголовок') ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'authorName', [
                'template' => '{label}{input}',
                'labelOptions' => ['class' => 'form-label']
            ])->textInput([
                'placeholder' => 'Введите имя автора',
                'class' => 'form-control'
            ])->label('Автор') ?>
        </div>

        <div class="col-12">
            <div class="d-flex gap-2">
                <?= Html::submitButton('<i class="fas fa-search me-1"></i> Искать', [
                    'class' => 'btn btn-primary'
                ]) ?>
                <?= Html::a('<i class="fas fa-times me-1"></i> Сбросить', ['index'], [
                    'class' => 'btn btn-outline-secondary'
                ]) ?>
                <?= Html::button('<i class="fas fa-chevron-up me-1"></i> Свернуть', [
                    'class' => 'btn btn-link text-decoration-none ms-auto',
                    'id' => 'toggle-search',
                    'data-bs-toggle' => 'collapse',
                    'data-bs-target' => '.search-form-fields',
                    'aria-expanded' => 'true',
                    'aria-controls' => 'searchFields'
                ]) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>