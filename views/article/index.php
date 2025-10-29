<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $article app\models\Article */

$this->title = Html::encode($article->title);
$this->params['breadcrumbs'][] = ['label' => 'Все статьи', 'url' => ['all']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<p><strong>Автор:</strong> <?= Html::encode($article->author->name ?? 'Неизвестен') ?></p>
<p><strong>Дата создания:</strong> <?= Yii::$app->formatter->asDatetime($article->created_at, 'php:d.m.Y H:i:s') ?></p> 
<p><strong>Дата обновления:</strong> <?= Yii::$app->formatter->asDatetime($article->updated_at, 'php:d.m.Y H:i:s') ?></p>
<p><?= Html::encode($article->content) ?></p>

<?php if ($article->img): ?>
    <img src="<?= Yii::getAlias('@web/img/' . Html::encode($article->img)) ?>" alt="<?= Html::encode($article->title) ?>" class="img-fluid" style="max-width: 100%; height: auto;">
<?php endif; ?>

<?php if ($article->user_id === Yii::$app->user->id): ?>
    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $article->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $article->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить эту статью?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
<?php endif; ?>

<a href="<?= \yii\helpers\Url::to(['article/all']) ?>">Назад к списку статей</a>
