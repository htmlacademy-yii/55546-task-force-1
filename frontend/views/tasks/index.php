<?php

use app\models\Task;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

$this->title = 'Новые задания';
$this->params['breadcrumbs'][] = $this->title;
$fieldConfig = ['template' => "{label}\n{input}", 'options' => ['tag' => false]];
?>

<section class="new-task">
    <div class="new-task__wrapper">
        <h1><?= $this->title; ?></h1>
        <?php
//        ListView::widget([
//            'dataProvider' => $provider,
//            'itemView' => 'task-card',
//            'itemOptions' => ['tag' => false],
//            'options' => ['tag' => false],
//            'summary' => '',
//
//
////            'pager' => [
////                'options' => [
////                    'class' => 'new-task__pagination-list',
////                ],
////                'linkContainerOptions' => [
////                    'class' => 'pagination__item',
////                ],
////                'activePageCssClass' => 'pagination__item--current',
////                'nextPageLabel' => '_',
////                'prevPageLabel' => '_',
////            ],
//        ]);
        ?>
        <?php foreach ($tasks as $task): ?>
            <div class="new-task__card">
                <div class="new-task__title">
                    <?= Html::a("<h2>$task->title</h2>", "/tasks/view/$task->id", ['class' => 'link-regular']); ?>
                    <?= Html::a("<p>{$task->category->title}</p>", Task::getUrlTasksByCategory($task->category->id), ['class' => 'new-task__type link-regular']); ?>
                </div>
                <div class="new-task__icon new-task__icon--<?= $task->category->code; ?>"></div>
                <p class="new-task_description"><?= $task->description; ?></p>
                <?php if($task->price): ?>
                    <b class="new-task__price new-task__price--<?= $task->category->code; ?>"><?= $task->price; ?><b> ₽</b></b>
                <?php endif; ?>
                <?php if($location = $task->getLocation()): ?>
                    <p class="new-task__place"><?= $location->AddressLine; ?></p>
                <?php endif; ?>
                <span class="new-task__time"><?= Yii::$app->formatter->asRelativeTime($task->date_start); ?></span>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="new-task__pagination">
        <?= \yii\widgets\LinkPager::widget([
            'pagination' => $pages,
            'options' => [
                'class' => 'new-task__pagination-list',
            ],
            'linkContainerOptions' => [
                'class' => 'pagination__item',
            ],
            'activePageCssClass' => 'pagination__item--current',
            'nextPageLabel' => '_',
            'prevPageLabel' => '_',
        ]); ?>
    </div>
</section>
<section  class="search-task">
    <div class="search-task__wrapper">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'options' => ['class' => 'search-task__form']]); ?>
            <fieldset class="search-task__categories">
                <legend>Категории</legend>
                <?= $form->field($taskModel, 'category')
                    ->checkboxList($categories, [
                    'item' => function ($_index, $label, $name, $checked, $id) {
                        $checked = $checked ? "checked" : "";
                        return "<input
                            class='visually-hidden checkbox__input'
                            type='checkbox'
                            name='$name'
                            id='category-$id'
                            value='$id'
                            $checked>
                            <label for='category-$id'>$label</label>";
                    }
                ])->label(false); ?>
            </fieldset>
            <fieldset class="search-task__categories">
                <legend>Дополнительно</legend>
                <?php
                foreach (['isNoExecutor', 'isTelework'] as $attr) {
                    echo $form->field($taskModel, $attr, ['template' => "{input}\n{label}", 'options' => ['tag' => false]])
                        ->checkbox(['class' => 'visually-hidden checkbox__input'], false);
                }
                ?>
            </fieldset>
            <?php
            echo $form->field($taskModel, 'time', $fieldConfig)
                ->dropDownList($period, ['class' => 'multiple-select input'])
                ->label('Период', ['class' => 'search-task__name']);
            echo $form->field($taskModel, 'title', $fieldConfig)
                ->textInput(['class' => 'input-middle input'])
                ->label('Поиск по названию', ['class' => 'search-task__name']);
            echo Html::submitButton('Искать', ['class' => 'button'])
            ?>
        <?php ActiveForm::end(); ?>
    </div>
</section>
