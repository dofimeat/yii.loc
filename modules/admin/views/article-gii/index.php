<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\bootstrap5\LinkPager;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\admin\models\ArticleSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Articles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="d-flex justify-content-between align-items-center mb-4">
        

        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'action' => ['index'],
            'options' => ['class' => 'form-inline']
        ]); ?>
        
        <div class="input-group">
            <?= $form->field($searchModel, 'pageSize', [
                'options' => ['class' => 'mb-0'],
                'inputOptions' => ['class' => 'form-select']
            ])->dropDownList([
                4 => '4 статьи',
                10 => '10 статей', 
                12 => '12 статей',
                0 => 'Все статьи'
            ], [
                'prompt' => 'Количество на странице',
                'onchange' => 'this.form.submit()'
            ])->label(false) ?>
        </div>
        
        <?php ActiveForm::end(); ?>
    </div>

    <?php Pjax::begin(); ?>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="summary mb-3">
        <?= \yii\helpers\Html::encode($dataProvider->getTotalCount() ? 
            "Показано " . $dataProvider->getCount() . " из " . $dataProvider->getTotalCount() . " статей" : 
            "Статьи не найдены"
        ) ?>
    </div>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => 'item',
        'layout' => "{pager}\n<div class='row g-2'>{items}</div>\n{pager}",
        'options' => ['class' => 'col-12'],
        'itemOptions' => ['class' => 'col-lg-6 col-md-6 mb-4'],
        'pager' => [
            'class' => LinkPager::class,
            'options' => ['class' => 'pagination justify-content-center mt-4'],
            'linkContainerOptions' => ['class' => 'page-item'],
            'linkOptions' => ['class' => 'page-link'],
            'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
        ],
        'emptyText' => 'No articles found.',
        'emptyTextOptions' => ['class' => 'col-12 text-muted text-center py-5'],
    ]) ?>

    <?php Pjax::end(); ?>
</div>