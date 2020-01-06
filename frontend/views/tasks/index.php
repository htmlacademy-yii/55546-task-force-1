<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<section class="new-task">
    <div class="new-task__wrapper">
        <h1>Новые задания</h1>
        <?php foreach ($tasks as $task): ?>
            <div class="new-task__card">
                <div class="new-task__title">
                    <a href="tasks/<?= $task->id; ?>" class="link-regular"><h2><?= $task->title; ?></h2></a>
                    <a  class="new-task__type link-regular" href="category/<?= $task->category_id; ?>">
                        <p><?= $task->category->title; ?></p>
                    </a>
                </div>
                <div class="new-task__icon new-task__icon--<?= $task->category->code; ?>"></div>
                <p class="new-task_description"><?= $task->description; ?></p>
                <b class="new-task__price new-task__price--<?= $task->category->code; ?>"><?= $task->price; ?><b> ₽</b></b>
                <p class="new-task__place"><?= $task->author->address; ?></p>
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
        <?php $form = ActiveForm::begin(['options' => ['class' => 'search-task__form', 'method' => 'post']]); ?>
            <fieldset class="search-task__categories">
                <legend>Категории</legend>
                <?= $form->field($taskModel, 'category')
                    ->checkboxList(yii\helpers\ArrayHelper::map($categories, 'id', 'title'),
                        ['item' => function ($_index, $title, $_name, $_checked, $id) use ($filters) {
                            $checked = isset($filters['category'][$id]) ? 'checked' : '';
                            return "<input
                                class='visually-hidden checkbox__input'
                                type='checkbox'
                                name='filters[category][$id]'
                                id='category-$id'
                                $checked>
                                <label for='category-$id'>$title</label>";
                        }]
                    )->label(false); ?>
            </fieldset>
            <fieldset class="search-task__categories">
                <legend>Дополнительно</legend>
                <?php
                    foreach ([
                        'isNoExecutor' => ['Без исполнителя', 'is-no-executor'],
                        'isTelework' => ['Удаленная работа', 'is-telework'],
                    ] as $attr => $data) {
                        echo $form->field($taskModel, $attr, [
                            'template' => "{input}\n{label}",
                            'options' => ['tag' => false]
                        ])->checkbox([
                            'class' => 'visually-hidden checkbox__input',
                            'name' => "filters[$data[1]]",
                            'checked' => $filters[$data[1]] === 'on',
                            'value' => 'on'
                        ], false)->label($data[0]);
                    }
                ?>
            </fieldset>
            <?php
            echo $form->field($taskModel, 'time', [
                'template' => "{label}\n{input}",
                'options' => ['tag' => false]
            ])->dropDownList(['day' => 'За день', 'week' => 'За неделю', 'month' => 'За месяц'],
                ['class' => 'multiple-select input', 'name' => 'filters[time]']
            )->label('Период', ['class' => 'search-task__name']);

            echo $form->field($taskModel, 'title', [
                'template' => "{label}\n{input}",
                'options' => ['tag' => false]
            ])->textInput([
                'class' => 'input-middle input',
                'name' => 'filters[title]'
            ])->label('Поиск по названию', ['class' => 'search-task__name']);

            echo Html::submitButton('Искать', ['class' => 'button'])
            ?>
        <?php ActiveForm::end(); ?>
    </div>
</section>
