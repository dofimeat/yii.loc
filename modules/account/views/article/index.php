<?php

use app\models\Article;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\modules\account\models\ArticleSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Мои статьи';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="article-index">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <?= Html::a('Создать статью', ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <?php Pjax::begin(); ?>

    <div class="row">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => 'index',
            'layout' => "{items}\n{pager}",
            'options' => ['class' => 'col-12'],
            'itemOptions' => ['class' => 'col-lg-4 col-md-6 mb-4'],
            'emptyText' => 'У вас пока нет статей.',
            'emptyTextOptions' => ['class' => 'col-12 text-muted text-center py-5'],
        ]) ?>
    </div>

    <?php Pjax::end(); ?>
</div>>
