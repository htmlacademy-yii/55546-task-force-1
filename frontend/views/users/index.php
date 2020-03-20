<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use common\models\User;

$fieldConfig = ['options' => ['tag' => false]];
?>

<section class="user__search">
    <div class="user__search-link">
        <p>Сортировать по:</p>
        <ul class="user__search-list">
            <li class="user__search-item user__search-item--current">
                <?= Html::a('Рейтингу', "?sort=".User::SORT_TYPE_RATING,
                    ['class' => 'link-regular']); ?>
            </li>
            <li class="user__search-item">
                <?= Html::a('Числу заказов', "?sort=".User::SORT_TYPE_ORDERS,
                    ['class' => 'link-regular']); ?>
            </li>
            <li class="user__search-item">
                <?= Html::a('Популярности', "?sort=".User::SORT_TYPE_POPULARITY,
                    ['class' => 'link-regular']); ?>
            </li>
        </ul>
    </div>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => 'executor-card',
        'itemOptions' => ['tag' => false],
        'options' => ['tag' => false],
        'summary' => '',
        'pager' => [
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
    ]); ?>
</section>
<section class="search-task">
    <div class="search-task__wrapper">
        <?php $form = ActiveForm::begin([
            'method' => 'GET',
            'options' => ['class' => 'search-task__form'],
        ]); ?>
        <fieldset class="search-task__categories">
            <legend>Категории</legend>
            <?= $form->field($model, 'categories', $fieldConfig)
                ->checkboxList($categories, [
                    'item' => function ($_index, $label, $name, $checked, $id) {
                        $checked = $checked ? "checked" : "";

                        return "<input
                                    class='visually-hidden checkbox__input'
                                    type='checkbox'
                                    id='categories-$id'
                                    name='$name'
                                    value='$id'
                                    $checked>
                                <label for='categories-$id'>$label </label>";
                    },
                    'tag' => false,
                ])->label(false); ?>
        </fieldset>
        <fieldset class="search-task__categories">
            <legend>Дополнительно</legend>
            <?= $form->field($model, 'additionally', $fieldConfig)
                ->checkboxList([
                    'now-free' => 'Сейчас свободен',
                    'now-online' => 'Сейчас онлайн',
                    'there-are-reviews' => 'Есть отзывы',
                    'in-favorites' => 'В избранном',
                ], [
                    'item' => function ($_index, $label, $name, $checked, $id) {
                        $checked = $checked ? 'checked' : '';

                        return "<input
                                    class='visually-hidden checkbox__input'
                                    type='checkbox'
                                    id='categories-$id'
                                    name='$name'
                                    value='$id'
                                    $checked>
                                <label for='categories-$id'>$label </label>";
                    },
                    'tag' => false,
                ])->label(false); ?>
        </fieldset>
        <?= $form->field($model, 'name',
            ['template' => '{label}{input}', 'options' => ['tag' => false]])
            ->input('search', ['class' => 'input-middle input'])
            ->label(null, ['class' => 'search-task__name']); ?>
        <?= Html::submitButton('Искать', ['class' => 'button']); ?>
        <?php ActiveForm::end(); ?>
    </div>
</section>
