<?php
use yii\helpers\Html;

$this->title = Html::encode($article->title);
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<p><?= Html::encode($article->content) ?></p>

<p>
    <?= Html::a('Вернуться к списку', ['index'], ['class' => 'btn btn-success']) ?>
    <?= Html::a('Удалить', ['delete', 'id' => $article->id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => 'Вы уверены, что хотите удалить эту статью?',
            'method' => 'post',
        ],
    ]) ?>
</p>
