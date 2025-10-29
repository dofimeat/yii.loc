<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->title ?></title>
</head>
<body>
    <div class="container">
        <div class="row">
            <?= $content ?>
        </div>
    </div>
    <?= isset($this->params['login']) ? $this->params['login'] : ''; ?>
    <?php $this->endBody() ?>
</body>
</html>