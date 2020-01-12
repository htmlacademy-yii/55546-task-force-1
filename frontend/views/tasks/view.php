<?php

use yii\helpers\Html;

$this->title = "Задание: $task->title";

?>

<section class="content-view">
    <div class="content-view__card">
        <div class="content-view__card-wrapper">
            <div class="content-view__header">
                <div class="content-view__headline">
                    <h1><?= $task->title; ?></h1>
                    <span>
                        Размещено в категории
                        <a href="#" class="link-regular"><?= $task->category->title; ?></a>
                        <?= $task->date_start; ?> назад
                    </span>
                </div>
                <b class="new-task__price new-task__price--<?= $task->category->code; ?> content-view-price">
                    <?= $task->price; ?><b> ₽</b>
                </b>
                <div class="new-task__icon new-task__icon--<?= $task->category->code; ?> content-view-icon"></div>
            </div>
            <div class="content-view__description">
                <h3 class="content-view__h3">Общее описание</h3>
                <p><?= $task->description; ?></p>
            </div>

            <?php if(count($task->files) > 0): ?>
                <div class="content-view__attach">
                    <h3 class="content-view__h3">Вложения</h3>
                    <?php foreach ($task->files as $file): ?>
                        <a href="#"><?= $file['file']; ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="content-view__location">
                <h3 class="content-view__h3">Расположение</h3>
                <div class="content-view__location-wrapper">
                    <div class="content-view__map">
                        <a href="#"><img src="/img/map.jpg" width="361" height="292"
                                         alt="Москва, Новый арбат, 23 к. 1"></a>
                    </div>
                    <div class="content-view__address">
                        <?= $task->author->address; ?>
                        <span class="address__town">Москва</span><br>
                        <span>Новый арбат, 23 к. 1</span>
                        <p>Вход под арку, код домофона 1122</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-view__action-buttons">
            <?php
               echo Html::button('Откликнуться', ['class' => 'button button__big-color response-button']);
               echo Html::button('Отказаться', ['class' => 'button button__big-color refusal-button']);
               echo Html::button('Написать сообщение', ['class' => 'button button__big-color connection-button']);
            ?>
        </div>
    </div>
    <div class="content-view__feedback">
        <h2>Отклики <span>(2)</span></h2>
        <div class="content-view__feedback-wrapper">
            <div class="content-view__feedback-card">
                <div class="feedback-card__top">
                    <a href="#"><img src="/img/man-glasses.jpg" width="55" height="55"></a>
                    <div class="feedback-card__top--name">
                        <p><a href="#" class="link-regular">Астахов Павел</a></p>
                        <span></span><span></span><span></span><span></span><span class="star-disabled"></span>
                        <b>4.25</b>
                    </div>
                    <span class="new-task__time">25 минут назад</span>
                </div>
                <div class="feedback-card__content">
                    <p>
                        Могу сделать всё в лучшем виде. У меня есть необходимый опыт и инструменты.
                    </p>
                    <span>1500 ₽</span>
                </div>
                <div class="feedback-card__actions">
                    <button class="button__small-color response-button button"
                            type="button">Откликнуться</button>
                    <button class="button__small-color refusal-button button"
                            type="button">Отказаться</button>
                    <button class="button__chat button"
                            type="button"></button>
                </div>
            </div>
            <div class="content-view__feedback-card">
                <div class="feedback-card__top">
                    <a href="#"><img src="/img/man-blond.jpg" width="55" height="55"></a>
                    <div class="feedback-card__top--name">
                        <p class="link-name"><a href="#" class="link-regular">Богатырев Дмитрий</a></p>
                        <span></span><span></span><span></span><span></span><span class="star-disabled"></span>
                        <b>4.25</b>
                    </div>
                    <span class="new-task__time">25 минут назад</span>
                </div>
                <div class="feedback-card__content">
                    <p>
                        Примусь за выполнение задания в течение часа, сделаю быстро и качественно.
                    </p>
                    <span>1500 ₽</span>
                </div>
                <div class="feedback-card__actions">
                    <button class="button__small-color response-button button"
                            type="button">Откликнуться</button>
                    <button class="button__small-color refusal-button button"
                            type="button">Отказаться</button>
                    <button class="button__chat button"
                            type="button"></button>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="connect-desk">
    <div class="connect-desk__profile-mini">
        <div class="profile-mini__wrapper">
            <h3>Заказчик</h3>
            <div class="profile-mini__top">
                <img src="/img/man-brune.jpg" width="62" height="62" alt="Аватар заказчика">
                <div class="profile-mini__name five-stars__rate">
                    <p><?= $task->author->name; ?></p>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span <?= ($task->author->rating > $i) ? '' : 'class="star-disabled"'; ?>></span>
                    <?php endfor; ?>
                    <b><?= $task->author->rating ?></b>
                </div>
            </div>
            <p class="info-customer">
                <span><?= count($task->reviewsCount); ?> отзывов</span>
                <span class="last-"><?= $task->author->order_count ?> заказов</span>
            </p>
            <a href="#" class="link-regular">Смотреть профиль</a>
        </div>
    </div>
    <div class="connect-desk__chat">
        <h3>Переписка</h3>
        <div class="chat__overflow">
            <div class="chat__message chat__message--out">
                <p class="chat__message-time">10.05.2019, 14:56</p>
                <p class="chat__message-text">Привет. Во сколько сможешь
                    приступить к работе?</p>
            </div>
            <div class="chat__message chat__message--in">
                <p class="chat__message-time">10.05.2019, 14:57</p>
                <p class="chat__message-text">На задание
                    выделены всего сутки, так что через час</p>
            </div>
            <div class="chat__message chat__message--out">
                <p class="chat__message-time">10.05.2019, 14:57</p>
                <p class="chat__message-text">Хорошо. Думаю, мы справимся</p>
            </div>
        </div>
        <p class="chat__your-message">Ваше сообщение</p>
        <form class="chat__form">
            <textarea class="input textarea textarea-chat" rows="2" name="message-text" placeholder="Текст сообщения"></textarea>
            <button class="button chat__button" type="submit">Отправить</button>
        </form>
    </div>
</section>
