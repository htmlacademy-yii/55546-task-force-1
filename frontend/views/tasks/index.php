<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;
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
        <?php $form = ActiveForm::begin([
            'options' => [
                'class' => 'search-task__form',
                'name' => 'test',
                'method' => 'post',
                'action' => '#'
            ]
        ]); ?>
            <fieldset class="search-task__categories">
                <legend>Категории</legend>
                <?php foreach ($categories as $category): ?>
                    <input class="visually-hidden checkbox__input" id="category-<?= $category->id; ?>"
                           type="checkbox" name="filters[category][<?= $category->id; ?>]"
                            <?= isset($filters['category'][$category->id]) ? 'checked' : ''; ?>>
                    <label for="category-<?= $category->id; ?>"><?= $category->title; ?></label>
                <?php endforeach; ?>
            </fieldset>
            <fieldset class="search-task__categories">
                <legend>Дополнительно</legend>
                <input class="visually-hidden checkbox__input" id="is-no-executor"
                       type="checkbox" name="filters[is-no-executor]"
                    <?= isset($filters['is-no-executor']) ? 'checked' : ''; ?>>
                <label for="is-no-executor">Без исполнителя </label>
                <input class="visually-hidden checkbox__input" id="is-telework"
                       type="checkbox" name="filters[is-telework]"
                    <?= isset($filters['is-telework']) ? 'checked' : ''; ?>>
                <label for="is-telework">Удаленная работа </label>
            </fieldset>
            <label class="search-task__name" for="time">Период</label>
            <select class="multiple-select input" id="time"size="1" name="filters[time]">
                <option value="day" <?= $filters['time'] === 'day' ? 'selected' : ''; ?>>За день</option>
                <option value="week" <?= $filters['time'] === 'week' ? 'selected' : ''; ?>>За неделю</option>
                <option value="month" <?= $filters['time'] === 'month' ? 'selected' : ''; ?>>За месяц</option>
            </select>
            <label class="search-task__name" for="title">Поиск по названию</label>
            <input class="input-middle input" id="title" type="search" name="filters[title]"
                value="<?= $filters['title'] ?? ''; ?>">
            <button class="button" type="submit">Искать</button>
        <?php ActiveForm::end(); ?>
    </div>
</section>
