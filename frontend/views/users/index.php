<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$fieldConfig = ['options' => ['tag' => false]];
?>

<section class="user__search">
    <div class="user__search-link">
        <p>Сортировать по:</p>
        <ul class="user__search-list">
            <li class="user__search-item user__search-item--current">
                <?= Html::a('Рейтингу', '#', ['class' => 'link-regular']); ?>
            </li>
            <li class="user__search-item">
                <?= Html::a('Числу заказов', '#', ['class' => 'link-regular']); ?>
            </li>
            <li class="user__search-item">
                <?= Html::a('Популярности', '#', ['class' => 'link-regular']); ?>
            </li>
        </ul>
    </div>
    <?php foreach ($executors as $executor): ?>
        <div class="content-view__feedback-card user__search-wrapper">
            <div class="feedback-card__top">
                <div class="user__search-icon">
                    <?= Html::a("<img src='{$executor->userData->getAvatar()}' width='65' height='65'>",
                            "users/view/$executor->id"); ?>
                    <span>17 заданий</span>
                    <span>6 отзывов</span>
                </div>
                <div class="feedback-card__top--name user__search-card">
                    <p class="link-name">
                        <?= Html::a($executor->login, "users/view/$executor->id", ['class' => 'link-regular']); ?>
                    </p>
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <span <?= $executor->userData->rating > $i ? '' : 'class="star-disabled"'; ?>></span>
                    <?php endfor; ?>
                    <b><?= $executor->userData->rating; ?></b>
                    <p class="user__search-content"><?= $executor->userData->description; ?></p>
                </div>
                <span class="new-task__time">Был на сайте 25 минут назад</span>
            </div>
            <div class="link-specialization user__search-link--bottom">
                <?php foreach ($executor->specializations as $specialization): ?>
                    <?= Html::a($specialization->title, '#', ['class' => 'link-regular']); ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</section>
<section class="search-task">
    <div class="search-task__wrapper">
        <?php $form = ActiveForm::begin(['options' => ['class' => 'search-task__form']]); ?>
        <fieldset class="search-task__categories">
            <legend>Категории</legend>

            <?= $form->field($model, 'categories', $fieldConfig)
                ->checkboxList($categories, [
                    'item' => function($_index, $label, $name, $checked, $id) {
                        return "<input
                                    class='visually-hidden checkbox__input'
                                    type='checkbox'
                                    id='categories-$id'
                                    name='$name'
                                    value='$id'>
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
                    'item' => function($_index, $label, $name, $checked, $id) {
                        return "<input
                                    class='visually-hidden checkbox__input'
                                    type='checkbox'
                                    id='categories-$id'
                                    name='$name'
                                    value='$id'>
                                <label for='categories-$id'>$label </label>";
                    },
                    'tag' => false,
                ])->label(false); ?>
        </fieldset>

        <?= $form->field($model, 'name', ['template' => '{label}{input}', 'options' => ['tag' => false]])
            ->input('search', ['class' => 'input-middle input'])
            ->label(null, ['class' => 'search-task__name']); ?>
        <?= Html::submitButton('Искать', ['class' => 'button']); ?>
        <?php ActiveForm::end(); ?>
    </div>
</section>
