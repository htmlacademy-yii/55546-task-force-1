<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\widgets\FrontendListView;
use frontend\widgets\FrontendPager;

$this->title = 'Новые задания';
$this->params['breadcrumbs'][] = Html::encode($this->title);
$fieldConfig = ['template' => "{label}\n{input}", 'options' => ['tag' => false]];

?>

<section class="new-task">
    <?= FrontendListView::widget([
        'dataProvider' => $provider,
        'itemView' => 'task-card',
        'itemOptions' => ['tag' => false],
        'options' => ['tag' => false],
        'summary' => Html::tag('h1', Html::encode($this->title)),
        'layout' => '{summary}' . PHP_EOL . '{items}' . PHP_EOL,
        'pager' => [
            'class' => FrontendPager::class,
            'options' => [
                'class' => 'new-task__pagination-list',
            ],
            'linkContainerOptions' => [
                'class' => 'pagination__item',
            ],
            'activePageCssClass' => 'pagination__item--current',
            'nextPageLabel' => '_',
            'prevPageLabel' => '_',
        ],
    ]);
    ?>
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
