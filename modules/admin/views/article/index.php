<?php
use yii\helpers\Html;

$this->title = 'Статьи';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<table class="table">
    <tr>
        <th>Название</th>
        <th>Действия</th>
    </tr>
    <?php foreach ($articles as $article): ?>
    <tr>
        <td><?= Html::encode($article->title) ?></td>
        <td>
            <?= Html::a('Просмотр', ['view', 'id' => $article->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $article->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить эту статью?',
                    'method' => 'post',
                ],
            ]) ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
