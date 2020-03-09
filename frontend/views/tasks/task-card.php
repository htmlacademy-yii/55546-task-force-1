<?php
use yii\helpers\Html;
use app\models\Task;

$task = $model;
?>

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
