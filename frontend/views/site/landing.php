<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Главная страница сайта TaskForce';

$fieldConfig = ['template' => "{label}{input}{error}", 'options' => ['tag' => false]];
?>

<div class="landing-container">
    <div class="landing-top">
        <h1>Работа для всех.<br>
            Найди исполнителя на любую задачу.</h1>
        <p>Сломался кран на кухне? Надо отправить документы? Нет времени самому гулять с собакой?
            У нас вы быстро найдёте исполнителя для любой жизненной ситуации?<br>
            Быстро, безопасно и с гарантией. Просто, как раз, два, три. </p>
        <?= Html::button('Создать аккаунт', ['class' => 'button']) ?>
    </div>
    <div class="landing-center">
        <div class="landing-instruction">
            <div class="landing-instruction-step">
                <div class="instruction-circle circle-request"></div>
                <div class="instruction-description">
                    <h3>Публикация заявки</h3>
                    <p>Создайте новую заявку.</p>
                    <p>Опишите в ней все детали
                        и  стоимость работы.</p>
                </div>
            </div>
            <div class="landing-instruction-step">
                <div class="instruction-circle  circle-choice"></div>
                <div class="instruction-description">
                    <h3>Выбор исполнителя</h3>
                    <p>Получайте отклики от мастеров.</p>
                    <p>Выберите подходящего<br>
                        вам исполнителя.</p>
                </div>
            </div>
            <div class="landing-instruction-step">
                <div class="instruction-circle  circle-discussion"></div>
                <div class="instruction-description">
                    <h3>Обсуждение деталей</h3>
                    <p>Обсудите все детали работы<br>
                        в нашем внутреннем чате.</p>
                </div>
            </div>
            <div class="landing-instruction-step">
                <div class="instruction-circle circle-payment"></div>
                <div class="instruction-description">
                    <h3>Оплата&nbsp;работы</h3>
                    <p>По завершении работы оплатите
                        услугу и закройте задание</p>
                </div>
            </div>
        </div>
        <div class="landing-notice">
            <div class="landing-notice-card card-executor">
                <h3>Исполнителям</h3>
                <ul class="notice-card-list">
                    <li>Большой выбор заданий</li>
                    <li>Работайте где  удобно</li>
                    <li>Свободный график</li>
                    <li>Удалённая работа</li>
                    <li>Гарантия оплаты</li>
                </ul>
            </div>
            <div class="landing-notice-card card-customer">
                <h3>Заказчикам</h3>
                <ul class="notice-card-list">
                    <li>Исполнители на любую задачу</li>
                    <li>Достоверные отзывы</li>
                    <li>Оплата по факту работы</li>
                    <li>Экономия времени и денег</li>
                    <li>Выгодные цены</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="landing-bottom">
        <div class="landing-bottom-container">
            <h2>Последние задания на сайте</h2>
            <?php foreach ($tasks as $task): ?>
                <div class="landing-task">
                    <div class="landing-task-top task-<?= $task->category->code === 'translation' ? 'courier' : $task->category->code; ?>"></div>
                    <div class="landing-task-description">
                        <h3><?= Html::a($task->title, '#', ['class' => 'link-regular']) ?></h3>
                        <p><?= $task->description; ?></p>
                    </div>
                    <div class="landing-task-info">
                        <div class="task-info-left">
                            <p><?= Html::a($task->category->title, '#', ['class' => 'link-regular']) ?></p>
                            <p><?= $task->date_start; ?> назад</p>
                        </div>
                        <span><?= $task->price; ?> <b>₽</b></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="landing-bottom-container">
            <?= Html::button('смотреть все задания', ['class' => 'button red-button']); ?>
        </div>
    </div>
</div>

<section class="modal enter-form form-modal" id="enter-form">
    <h2>Вход на сайт</h2>

    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true, 'enableClientValidation' => true]); ?>
        <p>
            <?= $form->field($model, 'email', $fieldConfig)
                ->input('email', ['class' => 'enter-form-email input input-middle', 'id' => 'enter-email'])
                ->label(null, ['class' => 'form-modal-description'])
                ->error(['id' => 'error-email']); ?>
        </p>
        <p>
            <?= $form->field($model, 'password', $fieldConfig)
                ->passwordInput(['class' => 'enter-form-email input input-middle', 'id' => 'enter-password'])
                ->label(null, ['class' => 'form-modal-description'])
                ->error(['id' => 'error-password']); ?>
        </p>
        <p>
            <?= \yii\authclient\widgets\AuthChoice::widget([
                'baseAuthUrl' => ['site/auth'],
                'popupMode' => false,
            ]); ?>
        </p>
        <?= Html::submitButton('Войти', ['id' => 'btn-login', 'class' => 'button']); ?>
    <?php ActiveForm::end(); ?>
    <?= Html::button('Закрыть', ['id' => 'close-modal', 'class' => 'form-modal-close']); ?>
</section>
<div class="overlay"></div>
