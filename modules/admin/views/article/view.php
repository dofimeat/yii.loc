<?php
use yii\helpers\Html;

$this->title = Html::encode($article->title);
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<p><?= Html::encode($article->content) ?></p>

<?php if ($article->img): ?>
    <img src="<?= Yii::getAlias('@web/img/' . Html::encode($article->img)) ?>" alt="<?= Html::encode($article->title) ?>" class="img-fluid" style="max-width: 450px; height: 450px;">
<?php endif; ?>

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
