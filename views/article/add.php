<?php

use yii\helpers\Html; 
use yii\bootstrap5\ActiveForm;

?>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <?= Yii::$app->session->getFlash('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="col-md-12">
    <h1>Добавление статьи</h1>
    <?php $form = ActiveForm::begin([
        'id' => 'article-form',
        // 'enableClientValidation' => false
    ]); ?>
    
    <?= $form->field($article, 'title')->textInput(['placeholder' => 'Введите заголовок страницы'])->label('Заголовок страницы') ?>
    <?= $form->field($article, 'name')->textInput(['placeholder' => 'Введите название статьи'])->label('Название статьи') ?>
    <?= $form->field($article, 'content')->textarea(['rows' => 7, 'placeholder' => 'Введите контент страницы'])->label('Контент страницы') ?>
    <?= $form->field($article, 'img')->fileInput()->label('Выберите файл изображения') ?>

    <div class="form-group">
        <?= Html::submitButton('Создать статью', ['class' => 'btn btn-primary']) ?> 
    </div>
    
    <?php ActiveForm::end(); ?>
</div>
