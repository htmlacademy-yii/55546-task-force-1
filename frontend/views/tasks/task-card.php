<?php

use yii\helpers\Html;
use app\models\Task;

$task = $model;
?>

<div class="new-task__card">
    <div class="new-task__title">
        <?= Html::a("<h2>".Html::encode($task->title ?? '')."</h2>",
            $task->getCurrentTaskUrl(), ['class' => 'link-regular']); ?>
        <?php if ($task->category): ?>
            <?= Html::a("<p>".Html::encode($task->category->title)."</p>",
                Task::getUrlTasksByCategory($task->category->id),
                ['class' => 'new-task__type link-regular']); ?>
        <?php endif; ?>
    </div>
    <?php if ($task->category): ?>
        <div
            class="new-task__icon new-task__icon--<?= $task->category->code; ?>"></div>
    <?php endif; ?>
    <p class="new-task_description"><?= Html::encode($task->description ??
            ''); ?></p>
    <?php if ($task->price): ?>
        <b class="new-task__price new-task__price--<?= $task->category->code; ?>"><?= Html::encode($task->price
                ?? ''); ?><b> ₽</b></b>
    <?php endif; ?>
    <?php if ($location = $task->getLocation()): ?>
        <p class="new-task__place"><?= Html::encode($location->AddressLine ??
                ''); ?></p>
    <?php endif; ?>
    <?php if ($task->date_start): ?>
        <span
            class="new-task__time"><?= Yii::$app->formatter->asRelativeTime($task->date_start); ?></span>
    <?php endif; ?>
</div>
