<?php

use common\models\User;
use yii\helpers\Html; ?>

<div class="content-view__feedback-card user__search-wrapper">
    <div class="feedback-card__top">
        <div class="user__search-icon">
            <?php $avatar = User::getCorrectAvatar($executor->avatar); ?>
            <?= Html::a("<img src='$avatar' width='65' height='65'>",
                "users/view/$executor->id]"); ?>
            <span>17 заданий</span>
            <span>6 отзывов</span>
        </div>
        <div class="feedback-card__top--name user__search-card">
            <p class="link-name">
                <?= Html::a($executor['login'], "users/view/$executor->user_id", ['class' => 'link-regular']); ?>
            </p>
            <?php for ($i = 0; $i < 5; $i++): ?>
                <span <?= $executor->rating > $i ? '' : 'class="star-disabled"'; ?>></span>
            <?php endfor; ?>
            <b><?= $executor['rating']; ?></b>
            <p class="user__search-content"><?= $executor->description; ?></p>
        </div>
        <span class="new-task__time"><?= Yii::$app->formatter->asRelativeTime($executor->last_activity); ?></span>
    </div>
    <div class="link-specialization user__search-link--bottom">
        <?php foreach ($executor->specializations as $specialization): ?>
            <?= Html::a($specialization->title, $executor->getUrlTasksByCategory($specialization->id), ['class' => 'link-regular']); ?>
        <?php endforeach; ?>
    </div>
</div>
