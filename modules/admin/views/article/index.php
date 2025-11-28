<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\bootstrap5\LinkPager;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ArticleSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Все статьи';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="article-index">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        
        <div class="d-flex align-items-center gap-3">
            <?php if (!Yii::$app->user->isGuest): ?>
                <?= Html::a('Создать статью', ['/account/article/create'], ['class' => 'btn btn-success']) ?>
            <?php else: ?>
                <div class="alert alert-warning mb-0">
                    Для написания статьи - <a href="<?= Url::to(['/site/login']) ?>" class="alert-link">авторизуйтесь</a>
                </div>
            <?php endif; ?>
            
            <!-- Поле выбора количества статей -->
            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'action' => ['index'],
                'options' => ['class' => 'form-inline']
            ]); ?>
            
            <div class="input-group">
                <?= $form->field($searchModel, 'pageSize', [
                    'options' => ['class' => 'mb-0'],
                    'inputOptions' => ['class' => 'form-select form-select-sm']
                ])->dropDownList([
                    4 => '4 статьи',
                    9 => '9 статей',
                    12 => '12 статей', 
                    0 => 'Все статьи'
                ], [
                    'prompt' => 'На странице',
                    'onchange' => 'this.form.submit()'
                ])->label(false) ?>
            </div>
            
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <!-- Сводка -->
    <div class="summary mb-3 text-muted">
        <?= \yii\helpers\Html::encode($dataProvider->getTotalCount() ? 
            "Найдено " . $dataProvider->getTotalCount() . " статей" : 
            "Статьи не найдены"
        ) ?>
    </div>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_item',
        'layout' => "{pager}\n<div class=\"row\">{items}</div>\n{pager}",
        'options' => ['class' => 'col-12'],
        'itemOptions' => ['class' => 'col-lg-4 col-md-6 mb-4'],
        'pager' => [
            'class' => LinkPager::class,
            'options' => ['class' => 'pagination justify-content-center mt-4'],
            'linkContainerOptions' => ['class' => 'page-item'],
            'linkOptions' => ['class' => 'page-link'],
            'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
        ],
        'emptyText' => 'Пока нет опубликованных статей.',
        'emptyTextOptions' => ['class' => 'col-12 text-muted text-center py-5'],
    ]) ?>

</div>