<?php

use yii\helpers\Html;
use app\models\Task;
use common\models\User;

$executor = $model;
?>

<div class="content-view__feedback-card user__search-wrapper">
    <div class="feedback-card__top">
        <div class="user__search-icon">
            <?php $avatar = User::getCorrectAvatar($executor['avatar']); ?>
            <?= Html::a("<img src='$avatar' width='65' height='65'>",
                User::getUserUrl((int)$executor['id'])); ?>
            <span><?= $executor['tasks_count'] ?? 0; ?> заданий</span>
            <span><?= $executor['reviews_count'] ?? 0; ?> отзывов</span>
        </div>
        <div class="feedback-card__top--name user__search-card">
            <p class="link-name">
                <?= Html::a(Html::encode($executor['login'] ?? ''),
                    User::getUserUrl((int)$executor['id']),
                    ['class' => 'link-regular']); ?>
            </p>

            <?php if ($executor['rating']): ?>
                <?php for ($i = 0; $i < 5; $i++): ?>
                    <span <?= $executor['rating'] > $i ? ''
                        : 'class="star-disabled"'; ?>></span>
                <?php endfor; ?>
                <b><?= $executor['rating']; ?></b>
            <?php endif; ?>
            <p class="user__search-content"><?= Html::encode($executor['description']
                    ?? ''); ?></p>
        </div>
        <?php if ($executor['last_activity']): ?>
            <span
                class="new-task__time"><?= Yii::$app->formatter->asRelativeTime($executor['last_activity']); ?></span>
        <?php endif; ?>
    </div>

    <?php if ($executor['specializations']): ?>
        <div class="link-specialization user__search-link--bottom">
            <?php foreach (
                json_decode($executor['specializations']) as $specialization
            ): ?>
                <?= Html::a(Html::encode($specialization->title),
                    Task::getUrlTasksByCategory((int)$specialization->id),
                    ['class' => 'link-regular']); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
