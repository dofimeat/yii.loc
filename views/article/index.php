<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Все статьи';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="article-index">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= Html::a('Создать статью', ['/account/article/create'], ['class' => 'btn btn-success']) ?>
        <?php else: ?>
            <div class="alert alert-warning mb-0">
                Для написания статьи - <a href="<?= Url::to(['/site/login']) ?>" class="alert-link">авторизуйтесь</a>
            </div>
        <?php endif; ?>
    </div>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_item',
        'layout' => "{items}\n{pager}",
        'options' => ['class' => 'row'],
        'itemOptions' => ['class' => 'col-lg-4 col-md-6 mb-4'],
        'emptyText' => 'Пока нет опубликованных статей.',
        'emptyTextOptions' => ['class' => 'col-12 text-muted text-center py-5'],
    ]) ?>

</div>