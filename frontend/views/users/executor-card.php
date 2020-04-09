<?php

use yii\helpers\Html;
use app\models\Task;
use common\models\User;

$executor = $model;

?>

<div class="content-view__feedback-card user__search-wrapper">
    <div class="feedback-card__top">
        <div class="user__search-icon">
            <?= Html::a("<img src='{$executor->userData->getAvatar()}' width='65' height='65'>",
                User::getUserUrl((int)$executor->id)); ?>
            <span><?= $executor->ordersCount ?? 0; ?> заданий</span>
            <span><?= $executor->reviewsCount ?? 0; ?> отзывов</span>
        </div>
        <div class="feedback-card__top--name user__search-card">
            <p class="link-name">
                <?= Html::a(Html::encode($executor->login ?? ''),
                    User::getUserUrl((int)$executor->id),
                    ['class' => 'link-regular']); ?>
            </p>

            <?php $rating = $executor->rating ?? 0; ?>
            <?php for ($i = 0; $i < 5; $i++): ?>
                <span <?= $rating > $i ? ''
                    : 'class="star-disabled"'; ?>></span>
            <?php endfor; ?>
            <b><?= $rating; ?></b>
            <p class="user__search-content"><?= Html::encode($executor->userData->description
                    ?? ''); ?></p>
        </div>
        <?php if ($executor->last_activity): ?>
            <span
                class="new-task__time"><?= Yii::$app->formatter->asRelativeTime($executor->last_activity); ?></span>
        <?php endif; ?>
    </div>

    <?php if ($executor->userSpecializations): ?>
        <div class="link-specialization user__search-link--bottom">
            <?php foreach ($executor->userSpecializations as $specialization): ?>
                <?= Html::a(Html::encode($specialization->title),
                    Task::getUrlTasksByCategory((int)$specialization->id),
                    ['class' => 'link-regular']); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
