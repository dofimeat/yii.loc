<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Мои статьи';
?>

<h1><?= Html::encode($this->title) ?></h1>

<table class="table">
    <thead>
        <tr>
            <th>Название</th>
            <th>Контент</th>
            <th>Изображение</th>
            <th>Создано</th>
            <th>Обновлено</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($articles as $article): ?>
        <tr>
            <td><?= Html::encode($article->title) ?></td>
            <td><?= Html::encode($article->content) ?></td>
            <td>
                <?php if ($article->img): ?>
                    <?= Html::img('@web/img/' . $article->img, ['alt' => 'Image', 'style' => 'width:100px;height:auto;']) ?>
                <?php else: ?>
                    Нет изображения
                <?php endif; ?>
            </td>
            <td><?= Yii::$app->formatter->asDatetime($article->created_at) ?></td>
            <td><?= Yii::$app->formatter->asDatetime($article->updated_at) ?></td>
            <td>
                <?= Html::a('Редактировать', ['article/update', 'id' => $article->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Удалить', ['article/delete', 'id' => $article->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить эту статью?',
                        'method' => 'post',
                    ],
                ]) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
