<?php

use app\models\Comment;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\modules\admin\models\CommentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Комментарии';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- <p>
        <?= Html::a('Создать комментарий', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?> -->

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => [
            'class' => 'item',
            'style' => 'display: inline-block; width: 300px; margin: 10px; vertical-align: top;',
        ],
        'itemView' => function ($model, $key, $index, $widget) {
            return $this->render('comment_item', [
                'model' => $model,
            ]);
        },
        'layout' => '{items}{pager}', 
    ]) ?>

    <?php Pjax::end(); ?>

</div>
