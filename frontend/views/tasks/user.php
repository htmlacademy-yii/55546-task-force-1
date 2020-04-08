<?php

use yii\helpers\Html;

?>
<div class="connect-desk__profile-mini">
    <div class="profile-mini__wrapper">
        <h3><?= $isExecutor ? 'Заказчик' : 'Исполнитель'; ?></h3>
        <div class="profile-mini__top">
            <img src="<?= $user->userData->getAvatar(); ?>"
                 width="62" height="62" alt="Аватар заказчика">
            <div class="profile-mini__name five-stars__rate">
                <p><?= Html::encode($user->login); ?></p>
                <?php if ($isExecutor): ?>
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <span <?= $user->userData->rating > $i ? ''
                            : 'class="star-disabled"'; ?>></span>
                    <?php endfor; ?>
                    <b><?= $user->userData->rating; ?></b>
                <?php endif; ?>
            </div>
        </div>
        <p class="info-customer">
            <?php if ($isExecutor): ?>
                <span><?= count($user->tasks); ?> заданий</span>
                <span
                    class="last-"><?= Yii::$app->formatter->asRelativeTime($user->date_registration); ?> на сайте</span>
            <?php else: ?>
                <span><?= $user->reviewsCount; ?> отзывов</span>
                <span class="last-"><?= $user->ordersCount; ?> заказов</span>
            <?php endif; ?>
        </p>
        <?php if ($isExecutor): ?>
            <?= Html::a('Смотреть профиль', $user->getCurrentUserUrl(),
                ['class' => 'link-regular']) ?>
        <?php endif; ?>
    </div>
</div>
