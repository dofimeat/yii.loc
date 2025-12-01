<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\bootstrap5\LinkPager;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\admin\models\ArticleSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Статьи';
$this->params['breadcrumbs'][] = $this->title;

$statusList = \app\models\Status::getItemsList();
$statusList = ['' => 'Все статусы'] + $statusList;
?>
<div class="article-index">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <?= Html::a('Создать статью', ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <!-- Единая форма поиска -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Поиск и фильтры</h5>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'action' => ['index'],
                'options' => [
                    'class' => 'row g-3',
                ],
            ]); ?>

            <!-- Первый ряд: сортировка, пагинация, статус -->
            <div class="col-md-4">
                <?= $form->field($searchModel, 'sortBy', [
                    'template' => '{label}{input}',
                    'labelOptions' => ['class' => 'form-label']
                ])->dropDownList([
                    'created_at-desc' => 'По дате (новые сначала)',
                    'created_at-asc' => 'По дате (старые сначала)',
                    'title-asc' => 'По названию (А-Я)',
                    'title-desc' => 'По названию (Я-А)',
                    'author.login-asc' => 'По автору (А-Я)',
                    'author.login-desc' => 'По автору (Я-А)',
                    'status.name-asc' => 'По статусу (А-Я)',
                    'status.name-desc' => 'По статусу (Я-А)',
                ], [
                    'prompt' => 'Выберите сортировку',
                    'class' => 'form-select'
                ])->label('Сортировка') ?>
            </div>

            <div class="col-md-4">
                <?= $form->field($searchModel, 'pageSize', [
                    'template' => '{label}{input}',
                    'labelOptions' => ['class' => 'form-label']
                ])->dropDownList([
                    4 => '4 статьи',
                    10 => '10 статей', 
                    12 => '12 статей',
                    18 => '18 статей',
                    0 => 'Все статьи'
                ], [
                    'prompt' => 'Количество на странице',
                    'class' => 'form-select'
                ])->label('Показывать') ?>
            </div>

            <div class="col-md-4">
                <?= $form->field($searchModel, 'status_id', [
                    'template' => '{label}{input}',
                    'labelOptions' => ['class' => 'form-label']
                ])->dropDownList(
                    $statusList,
                    [
                        'prompt' => 'Все статусы',
                        'class' => 'form-select'
                    ]
                )->label('Статус') ?>
            </div>

            <!-- Второй ряд: поиск по заголовку и автору -->
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <?= $form->field($searchModel, 'title', [
                        'options' => ['class' => 'mb-0 flex-grow-1'],
                        'inputOptions' => [
                            'class' => 'form-control',
                            'placeholder' => 'Поиск по заголовку...'
                        ]
                    ])->label(false) ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-user"></i>
                    </span>
                    <?= $form->field($searchModel, 'authorName', [
                        'options' => ['class' => 'mb-0 flex-grow-1'],
                        'inputOptions' => [
                            'class' => 'form-control',
                            'placeholder' => 'Поиск по автору...'
                        ]
                    ])->label(false) ?>
                </div>
            </div>

            <!-- Кнопки -->
            <div class="col-12">
                <div class="d-flex gap-2">
                    <?= Html::submitButton('<i class="fas fa-search me-1"></i> Применить фильтры', [
                        'class' => 'btn btn-primary'
                    ]) ?>
                    <?= Html::a('<i class="fas fa-times me-1"></i> Сбросить все', ['index'], [
                        'class' => 'btn btn-outline-secondary'
                    ]) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <?php Pjax::begin(); ?>

    <div class="summary mb-3">
        <?php
        $total = $dataProvider->getTotalCount();
        if ($total > 0) {
            $current = $dataProvider->getCount();
            $from = $dataProvider->pagination->getOffset() + 1;
            $to = $dataProvider->pagination->getOffset() + $current;
            
            $message = "Показано статьи $from-$to из $total";
            
            // Добавляем информацию о фильтрах
            $filters = [];
            if ($searchModel->title) {
                $filters[] = "заголовок: «{$searchModel->title}»";
            }
            if ($searchModel->authorName) {
                $filters[] = "автор: «{$searchModel->authorName}»";
            }
            if ($searchModel->status_id && isset($statusList[$searchModel->status_id])) {
                $filters[] = "статус: {$statusList[$searchModel->status_id]}";
            }
            
            if (!empty($filters)) {
                $message .= " (фильтры: " . implode(', ', $filters) . ")";
            }
            
            echo \yii\helpers\Html::encode($message);
        } else {
            echo "Статьи не найдены";
            if ($searchModel->title || $searchModel->authorName || $searchModel->status_id) {
                echo ". Попробуйте изменить параметры поиска.";
            }
        }
        ?>
    </div>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => 'item',
        'layout' => "{pager}\n<div class='row g-2'>{items}</div>\n{pager}",
        'options' => ['class' => 'col-12'],
        'itemOptions' => ['class' => 'col-lg-6 col-md-6 mb-4'],
        'pager' => [
            'class' => LinkPager::class,
            'options' => ['class' => 'pagination justify-content-center mt-4'],
            'linkContainerOptions' => ['class' => 'page-item'],
            'linkOptions' => ['class' => 'page-link'],
            'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
        ],
        'emptyText' => 'Статьи не найдены. Попробуйте изменить параметры поиска.',
        'emptyTextOptions' => ['class' => 'col-12 text-muted text-center py-5'],
    ]) ?>

    <?php Pjax::end(); ?>
</div>