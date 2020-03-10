<?php
use yii\helpers\Html;
use app\models\Task;

?>
<section class="content-view">
    <div class="user__card-wrapper">
        <div class="user__card">
            <img src="<?= $user->userData->getAvatar(); ?>" width="120" height="120" alt="Аватар пользователя">
            <div class="content-view__headline">
                <h1><?= $user->login; ?></h1>
                <?php if($user->city): ?>
                    <p><?= $user->city->name; ?></p>
                <?php endif; ?>
                <div class="profile-mini__name five-stars__rate">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <span <?= $user->userData->rating > $i ? '' : 'class="star-disabled"'; ?>></span>
                    <?php endfor; ?>
                    <b><?= $user->userData->rating; ?></b>
                </div>
                <b class="done-task">Выполнил 5 заказов</b><b class="done-review">Получил 6 отзывов</b>
            </div>
            <div class="content-view__headline user__card-bookmark <?= $isFavorite ? 'user__card-bookmark--current' : ''; ?>">
                <span>Был на сайте <?= Yii::$app->formatter->asRelativeTime($user->last_activity); ?></span>
                <?= Html::a('<b></b>', "/users/select-favorite?userId={$user->id}"); ?>
            </div>
        </div>
        <div class="content-view__description">
            <p><?= $user->userData->description; ?></p>
        </div>
        <div class="user__card-general-information">
            <div class="user__card-info">
                <h3 class="content-view__h3">Специализации</h3>
                <div class="link-specialization">
                    <?php foreach ($user->specializations as $specialization): ?>
                        <?= Html::a($specialization->title, Task::getUrlTasksByCategory($specialization->id), ['class' => 'link-regular']) ?>
                    <?php endforeach; ?>
                </div>
                <?php if (!$user->userSettings->is_hidden_contacts || $isCustomer): ?>
                    <h3 class="content-view__h3">Контакты</h3>
                    <div class="user__card-link">
                        <?= Html::a($user->userData->phone, '#', ['class' => 'user__card-link--tel link-regular']); ?>
                        <?= Html::a($user->email, '#', ['class' => 'user__card-link--email link-regular']); ?>
                        <?= Html::a($user->userData->skype, '#', ['class' => 'user__card-link--skype link-regular']); ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if($user->photo): ?>
                <div class="user__card-photo">
                    <h3 class="content-view__h3">Фото работ</h3>
                    <?php foreach ($user->photo as $data): ?>
                        <?= Html::a("<img src='$data->photo' width='85' height='86' alt='Фото работы'>", '#') ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php if($user->reviews): ?>
        <div class="content-view__feedback">
            <h2>Отзывы<span>(<?= count($user->reviews) ?>)</span></h2>
            <div class="content-view__feedback-wrapper reviews-wrapper">
                <?php foreach ($user->reviews as $review): ?>
                    <div class="feedback-card__reviews">
                        <p class="link-task link">Задание «<?= Html::a($review->task->title, "/tasks/view/{$review->task->id}", ['class' => 'link-regular']); ?>»</p>
                        <div class="card__review">
                            <?= Html::a("<img src='{$review->author->userData->getAvatar()}' width='55' height='54'>", "#"); ?>
                            <div class="feedback-card__reviews-content">
                                <p class="link-name link"><?= Html::a($review->author->login, "#", ['class' => 'link-regular']); ?></p>
                                <p class="review-text"><?= $review->text; ?></p>
                            </div>
                            <div class="card__review-rate">
                                <p class="<?= $review->rating > 3 ? 'five-rate' : 'three-rate'; ?> big-rate"><?= $review->rating; ?><span></span></p>
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
