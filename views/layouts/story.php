<?php

use yii\helpers\Html;
use app\assets\StoryAsset;

/* @var $this \yii\web\View */
/* @var $content string */

StoryAsset::register($this);

$this->beginPage(); 
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags(); ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="container">
    <header>
        <h1><?= Html::encode($this->title) ?></h1>
    </header>

    <main>
        <?= $content ?>
    </main>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage(); ?>
