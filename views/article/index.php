<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $article app\models\Article */
/* @var $comments app\models\Comment[] */
/* @var $newComment app\models\Comment */

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

<h2>Комментарии</h2>

<div id="comments">
    <?php if (!empty($comments)): ?>
        <?php foreach ($comments as $comment): ?>
            <div class="comment">
                <p><strong><?= Html::encode($comment->user->login) ?></strong>: <?= Html::encode($comment->content) ?></p>
                <p><small><?= Yii::$app->formatter->asDatetime($comment->created_at) ?></small></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Нет комментариев.</p>
    <?php endif; ?>
</div>

<div class="comment-form">
    <h3>Добавить комментарий</h3>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($newComment, 'content')->textarea(['rows' => 4]) ?>

    <div class="form-group">
        <?= Html::submitButton('Добавить комментарий', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
