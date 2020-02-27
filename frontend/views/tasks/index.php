<?php
use yii\helpers\{Html, Url};
use yii\widgets\ActiveForm;

$this->title = 'Новые задания';
$this->params['breadcrumbs'][] = $this->title;
$fieldConfig = ['template' => "{label}\n{input}", 'options' => ['tag' => false]];
?>

<section class="new-task">
    <div class="new-task__wrapper">
        <h1><?= $this->title; ?></h1>
        <?php foreach ($tasks as $task): ?>
            <div class="new-task__card">
                <div class="new-task__title">
                    <a href="<?= Url::to(['view', 'id' => $task->id]); ?>" class="link-regular"><h2><?= $task->title; ?></h2></a>
                    <a  class="new-task__type link-regular" href="category/<?= $task->category_id; ?>">
                        <p><?= $task->category->title; ?></p>
                    </a>
                </div>
                <div class="new-task__icon new-task__icon--<?= $task->category->code; ?>"></div>
                <p class="new-task_description"><?= $task->description; ?></p>
                <b class="new-task__price new-task__price--<?= $task->category->code; ?>"><?= $task->price; ?><b> ₽</b></b>
                <p class="new-task__place"><?= $task->author->userData->address; ?></p>
                <span class="new-task__time"><?= $task->date_start; ?></span>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="new-task__pagination">
        <ul class="new-task__pagination-list">
            <li class="pagination__item"><a href="#"></a></li>
            <li class="pagination__item pagination__item--current">
                <a>1</a></li>
            <li class="pagination__item"><a href="#">2</a></li>
            <li class="pagination__item"><a href="#">3</a></li>
            <li class="pagination__item"><a href="#"></a></li>
        </ul>
    </div>
</section>
<section  class="search-task">
    <div class="search-task__wrapper">
        <?php $form = ActiveForm::begin(['options' => ['class' => 'search-task__form']]); ?>
            <fieldset class="search-task__categories">
                <legend>Категории</legend>
                <?= $form->field($taskModel, 'category')
                    ->checkboxList(yii\helpers\ArrayHelper::map($categories, 'id', 'title'), [
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
