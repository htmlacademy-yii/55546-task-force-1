<?php

use yii\helpers\Html;
use app\models\Task;

?>
<section class="content-view">
    <div class="user__card-wrapper">
        <div class="user__card">
            <img src="<?= $user->userData->getAvatar(); ?>" width="120"
                 height="120" alt="Аватар пользователя">
            <div class="content-view__headline">
                <h1><?= Html::encode($user->login ?? ''); ?></h1>
                <?php if ($user->city): ?>
                    <p><?= Html::encode($user->city->name ?? ''); ?></p>
                <?php endif; ?>
                <div class="profile-mini__name five-stars__rate">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <span <?= $user->rating > $i ? ''
                            : 'class="star-disabled"'; ?>></span>
                    <?php endfor; ?>
                    <b><?= $user->rating; ?></b>
                </div>
                <b class="done-task">Выполнил <?= $user->completedTasksCount ??
                    0; ?>
                    заказов</b>
                <b class="done-review">Получил <?= $user->reviewsCount ?? 0; ?>
                    отзывов</b>
            </div>
            <div
                class="content-view__headline user__card-bookmark <?= $isFavorite
                    ? 'user__card-bookmark--current' : ''; ?>">
                <?php if ($user->last_activity): ?>
                    <span>Был на сайте <?= Yii::$app->formatter->asRelativeTime($user->last_activity); ?></span>
                <?php endif; ?>
                <?= Html::a('<b></b>', $user->getFavoriteUrl()); ?>
            </div>
        </div>
        <div class="content-view__description">
            <p><?= Html::encode($user->userData->description ?? ''); ?></p>
        </div>
        <div class="user__card-general-information">
            <div class="user__card-info">
                <h3 class="content-view__h3">Специализации</h3>
                <div class="link-specialization">
                    <?php foreach (
                        $user->userSpecializations as $specialization
                    ):
                        ?>
                        <?= Html::a(Html::encode($specialization->title),
                        Task::getUrlTasksByCategory($specialization->id),
                        ['class' => 'link-regular']) ?>
                    <?php endforeach; ?>
                </div>
                <?php if ($isOwner || !$user->userSettings->is_hidden_contacts
                    || $isCustomer
                ): ?>
                    <h3 class="content-view__h3">Контакты</h3>
                    <div class="user__card-link">
                        <?= Html::a(Html::encode($user->userData->phone ?? ''),
                            '#',
                            ['class' => 'user__card-link--tel link-regular']); ?>
                        <?= Html::a(Html::encode($user->email ?? ''), '#',
                            ['class' => 'user__card-link--email link-regular']); ?>
                        <?= Html::a(Html::encode($user->userData->skype ?? ''),
                            '#',
                            ['class' => 'user__card-link--skype link-regular']); ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($photos = $user->photos): ?>
                <div class="user__card-photo">
                    <h3 class="content-view__h3">Фото работ</h3>
                    <?php foreach ($photos as $data): ?>
                        <?= Html::a("<img src='/$data->photo' width='85' height='86' alt='Фото работы'>",
                            "/$data->photo", ['target' => '_blank']) ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php if ($user->reviews): ?>
        <div class="content-view__feedback">
            <h2>Отзывы<span>(<?= count($user->reviews) ?>)</span></h2>
            <div class="content-view__feedback-wrapper reviews-wrapper">
                <?php foreach ($user->reviews as $review): ?>
                    <div class="feedback-card__reviews">
                        <p class="link-task link">Задание
                            «<?= Html::a(Html::encode($review->task->title),
                                $review->task->getCurrentTaskUrl(),
                                ['class' => 'link-regular']); ?>»</p>
                        <div class="card__review">
                            <?= Html::a("<img src='{$review->author->userData->getAvatar()}' width='55' height='54'>",
                                $review->author->getCurrentUserUrl()); ?>
                            <div class="feedback-card__reviews-content">
                                <p class="link-name link"><?= Html::a(Html::encode($review->author->login),
                                        $review->author->getCurrentUserUrl(),
                                        ['class' => 'link-regular']); ?></p>
                                <p class="review-text"><?= Html::encode($review->text); ?></p>
                            </div>
                            <div class="card__review-rate">
                                <p class="<?= $review->rating > 3 ? 'five-rate'
                                    : 'three-rate'; ?> big-rate"><?= $review->rating; ?>
                                    <span></span></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>
<section class="connect-desk">
    <div class="connect-desk__chat">

    </div>
</section>
