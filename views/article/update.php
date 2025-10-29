<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $article app\models\Article */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Редактировать статью: ' . Html::encode($article->title);
$this->params['breadcrumbs'][] = ['label' => 'Все статьи', 'url' => ['all']];
$this->params['breadcrumbs'][] = $this->title;

?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="article-form">
    
    <?php if ($article->img): ?>
        <div class="current-image">
            <h4>Текущее изображение:</h4>
            <img src="<?= Yii::getAlias('@web/img/' . Html::encode($article->img)) ?>" alt="<?= Html::encode($article->title) ?>" style="max-width: 200px; height: auto;">
        </div>
    <?php endif; ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($article, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($article, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($article, 'img')->fileInput()->label('Загрузить новое изображение (если нужно)') ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<a href="<?= \yii\helpers\Url::to(['article/all']) ?>">Назад к списку статей</a>