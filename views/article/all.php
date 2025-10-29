<?php
use yii\helpers\Html;

$this->title = 'Все статьи'; 
?>

<h1><?= Html::encode($this->title) ?></h1>

<ul class="list-group">
    <?php foreach ($articles as $article): ?>
        <li class="list-group-item">
            <h5><?= Html::encode($article->title) ?></h5>
            <p>Автор: <?= Html::encode($article->author->name ?? 'Неизвестен') ?></p>
            <p><?= Html::encode($article->name) ?></p>
            <p><?= Html::encode($article->content) ?></p> 
            
            <?php if ($article->img): ?>
                <img src="<?= Yii::getAlias('@web/img/' . Html::encode($article->img)) ?>" alt="<?= Html::encode($article->title) ?>" class="img-fluid" style="max-width: 100%; height: auto;">
            <?php else: ?>
                <p>Изображение нет. </p>
            <?php endif; ?>
            <br>
            <a href="<?= \yii\helpers\Url::to(['article/' . $article->id]) ?>">Открыть статью</a> 
        </li>
    <?php endforeach; ?>
</ul>
