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

            <div class="col-md-4">
                <?= $form->field($searchModel, 'sortBy', [
                    'template' => '{label}{input}',
                    'labelOptions' => ['class' => 'form-label']
                    ])->dropDownList([
                        'created_at-desc' => 'По дате (новые сначала)',
                        'created_at-asc' => 'По дате (старые сначала)',
                        'title-asc' => 'По названию статьи (А-Я)',
                        'title-desc' => 'По названию статьи (Я-А)',
                        'author.name-asc' => 'По автору (А-Я)',
                        'author.name-desc' => 'По автору (Я-А)',
                        'status.status-asc' => 'По статусу (А-Я)',
                        'status.status-desc' => 'По статусу (Я-А)',
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
                )->label('Статус (по ID)') ?>
            </div>

            <div class="col-md-4">
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

            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-user"></i>
                    </span>
                    <?= $form->field($searchModel, 'author_name', [
                        'options' => ['class' => 'mb-0 flex-grow-1'],
                        'inputOptions' => [
                            'class' => 'form-control',
                            'placeholder' => 'Поиск по автору...'
                        ]
                    ])->label(false) ?>
                </div>
            </div>

            <div class="col-md-4"></div>

            <div class="col-md-4">
                <?= $form->field($searchModel, 'created_at_from', [
                    'template' => '{label}{input}',
                    'labelOptions' => ['class' => 'form-label']
                ])->input('date', [
                    'class' => 'form-control',
                    'placeholder' => 'Дата от...',
                    'title' => 'Выберите начальную дату'
                ])->label('Дата создания (от)') ?>
            </div>

            <div class="col-md-4">
                <?= $form->field($searchModel, 'created_at_to', [
                    'template' => '{label}{input}',
                    'labelOptions' => ['class' => 'form-label']
                ])->input('date', [
                    'class' => 'form-control',
                    'placeholder' => 'Дата до...',
                    'title' => 'Выберите конечную дату'
                ])->label('Дата создания (до)') ?>
            </div> 

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
            
            $filters = [];
            if ($searchModel->title) {
                $filters[] = "заголовок: «{$searchModel->title}»";
            }
            if ($searchModel->author_name) {
                $filters[] = "автор: «{$searchModel->author_name}»";
            }
            if ($searchModel->status_id && isset($statusList[$searchModel->status_id])) {
                $filters[] = "ID статуса: {$statusList[$searchModel->status_id]}";
            }
            if ($searchModel->created_at_from) {
                $filters[] = "дата от: {$searchModel->created_at_from}";
            }
            if ($searchModel->created_at_to) {
                $filters[] = "дата до: {$searchModel->created_at_to}";
            }
            
            if (!empty($filters)) {
                $message .= " (фильтры: " . implode(', ', $filters) . ")";
            }
            
            echo \yii\helpers\Html::encode($message);
        } else {
            echo "Статьи не найдены";
            if ($searchModel->title || $searchModel->author_name || $searchModel->status_id || $searchModel->created_at_from || $searchModel->created_at_to) {
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