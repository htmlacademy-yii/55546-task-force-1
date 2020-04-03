<?php

use frontend\assets\TaskViewAsset;
use frontend\assets\YandexMapAsset;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\TaskRespond;
use app\models\Task;
use yii\helpers\Url;

$this->title = "Задание: $task->title";

$fieldConfig = ['template' => '<p>{label}{input}{error}</p>'];
$respondsCount = count($task->responds);

YandexMapAsset::register($this);
TaskViewAsset::register($this);
?>

<section class="content-view">
    <div class="content-view__card">
        <div class="content-view__card-wrapper">
            <div class="content-view__header">
                <div class="content-view__headline">
                    <h1><?= $task->title; ?></h1>
                    <span>
                        Размещено в категории
                        <?php if ($task->category): ?>
                            <?= Html::a(Html::encode($task->category->title),
                                Task::getUrlTasksByCategory($task->category->id),
                                ['class' => 'link-regular']) ?>
                        <?php endif; ?>
                        <?= $task->date_start
                            ? Yii::$app->formatter->asRelativeTime($task->date_start)
                            : ''; ?>
                    </span>
                </div>
                <b class="new-task__price new-task__price--<?= $task->category
                    ? $task->category->code : ''; ?> content-view-price">
                    <?= Html::encode($task->price ?? ''); ?><b> ₽</b>
                </b>
                <div class="new-task__icon new-task__icon--<?= $task->category
                    ? $task->category->code : ''; ?> content-view-icon"></div>
            </div>
            <div class="content-view__description">
                <h3 class="content-view__h3">Общее описание</h3>
                <p><?= Html::encode($task->description ?? ''); ?></p>
            </div>

            <?php if (count($task->files) > 0): ?>
                <div class="content-view__attach">
                    <h3 class="content-view__h3">Вложения</h3>
                    <?php foreach ($task->files as $file): ?>
                        <?= Html::a($task->getCorrectFileName($file['file']),
                            "/$file[file]", ['download' => true]); ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="content-view__location">
                <h3 class="content-view__h3">Расположение</h3>
                <div class="content-view__location-wrapper">
                    <div class="content-view__map" id="map"
                         style="width: 361px; height: 292px;">
                        <?php if ($task->latitude && $task->longitude): ?>
                            <?= Html::hiddenInput('location-position',
                                "$task->latitude $task->longitude"); ?>
                        <?php else: ?>
                            <?= Html::a('<img src="/img/map.jpg" width="361" height="292"
                                             alt="Москва, Новый арбат, 23 к. 1">',
                                "#") ?>
                        <?php endif; ?>
                    </div>
                    <div class="content-view__address">
                        <?php if ($taskLocation): ?>
                            <span class="address__town">
                                <?= Html::encode($task->location->AddressLine ??
                                    ''); ?>
                            </span>
                            <br>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-view__action-buttons">
            <?php
            if ($task->status === Task::STATUS_NEW
                || $task->status === Task::STATUS_EXECUTION
            ) {
                if ($isAuthor) {
                    if ($task->status === Task::STATUS_EXECUTION) {
                        echo Html::button('Завершить', [
                            'class' => 'button button__big-color request-button open-modal',
                            'data-for' => 'complete-form',
                        ]);
                    } else {
                        echo Html::a('Отмена', "/tasks/cancel/$task->id", [
                            'class' => 'button button__big-color refusal-button open-modal',
                            'data-for' => 'canceled-form',
                        ]);
                    }
                } elseif ($isExecutor) {
                    if ($isRespond && $isSelectedExecutor) {
                        echo Html::button('Отказаться', [
                            'class' => 'button button__big-color refusal-button open-modal',
                            'data-for' => 'refuse-form',
                        ]);
                    } elseif (!$isRespond) {
                        echo Html::button('Откликнуться', [
                            'class' => 'button button__big-color response-button open-modal',
                            'data-for' => 'response-form',
                        ]);
                    }
                }
            }
            ?>
        </div>
    </div>
    <?php if ($respondsCount > 0): ?>
        <div class="content-view__feedback">
            <h2>Отклики <span>(<?= $respondsCount; ?>)</span></h2>
            <div class="content-view__feedback-wrapper">
                <?php foreach ($task->responds as $respond): ?>
                    <?php if ($isAuthor
                        || Yii::$app->user->identity->id === $respond->user_id
                    ): ?>
                        <div class="content-view__feedback-card">
                            <div class="feedback-card__top">
                                <?= Html::a("<img src='{$respond->user->userData->getAvatar()}' width='55' height='55'>",
                                    $respond->user->getCurrentUserUrl()) ?>
                                <div class="feedback-card__top--name">
                                    <p><?= Html::a(Html::encode($respond->user->login),
                                            $respond->user->getCurrentUserUrl(),
                                            ['class' => 'link-regular']); ?></p>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span <?= ($respond->user->userData->rating
                                            >= $i) ? ''
                                            : 'class="star-disabled"'; ?>></span>
                                    <?php endfor; ?>
                                    <b><?= $respond->user->userData->rating; ?></b>
                                </div>
                                <span
                                    class="new-task__time"><?= Yii::$app->formatter->asRelativeTime($respond->public_date); ?></span>
                            </div>
                            <div class="feedback-card__content">
                                <p><?= Html::encode($respond->text); ?></p>
                                <span><?= Html::encode($respond->price); ?> ₽</span>
                            </div>
                            <div class="feedback-card__actions">
                                <?php
                                if ($isAuthor
                                    && $respond->status
                                    === TaskRespond::STATUS_NEW
                                    && $task->status === Task::STATUS_NEW
                                ) {
                                    echo Html::a('Подтвердить',
                                        Url::to("/tasks/decision/{$respond->id}/accepted"),
                                        [
                                            'class' => 'button__small-color request-button button',
                                        ]);
                                    echo Html::a('Отказать',
                                        Url::to("/tasks/decision/{$respond->id}/denied"),
                                        [
                                            'class' => 'button__small-color refusal-button button',
                                        ]);
                                }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>

<section class="connect-desk">
    <?php if ($executor && $isAuthor): ?>
        <div class="connect-desk__profile-mini">
            <div class="profile-mini__wrapper">
                <h3>Исполнитель</h3>
                <div class="profile-mini__top">
                    <img src="<?= $executor->userData->getAvatar(); ?>"
                         width="62" height="62" alt="Аватар заказчика">
                    <div class="profile-mini__name five-stars__rate">
                        <p><?= Html::encode($executor->login); ?></p>
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <span <?= $executor->userData->rating > $i ? ''
                                : 'class="star-disabled"'; ?>></span>
                        <?php endfor; ?>
                        <b><?= $executor->userData->rating; ?></b>
                    </div>
                </div>

                <p class="info-customer">
                    <span><?= $executor->reviewsCount; ?> отзывов</span>
                    <span
                        class="last-"><?= $executor->ordersCount; ?> заказов</span>
                </p>
                <?= Html::a('Смотреть профиль', $executor->getCurrentUserUrl(),
                    ['class' => 'link-regular']) ?>
            </div>
        </div>
    <?php else: ?>
        <div class="connect-desk__profile-mini">
            <div class="profile-mini__wrapper">
                <h3>Заказчик</h3>
                <div class="profile-mini__top">
                    <img src="<?= $task->author->userData->getAvatar(); ?>"
                         width="62" height="62" alt="Аватар заказчика">
                    <div class="profile-mini__name five-stars__rate">
                        <p><?= Html::encode($task->author->login); ?></p>
                    </div>
                </div>
                <p class="info-customer">
                    <span><?= count($task->author->tasks); ?> заданий</span>
                    <span
                        class="last-"><?= Yii::$app->formatter->asRelativeTime($task->author->date_registration); ?> на сайте</span>
                </p>
                <?= Html::a('Смотреть профиль',
                    $task->author->getCurrentUserUrl(),
                    ['class' => 'link-regular']) ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($task->executor_id && ($isAuthor || $isSelectedExecutor)): ?>
        <div id="chat-container">
            <chat class="connect-desk__chat" task="<?= $task->id; ?>"></chat>
        </div>
    <?php endif; ?>
</section>

<section class="modal response-form form-modal" id="response-form">
    <h2>Отклик на задание</h2>
    <?php $form = ActiveForm::begin([
        'action' => Url::to("/tasks/respond/{$task->id}"),
        'enableClientValidation' => false,
        'enableAjaxValidation' => true,
        'validationUrl' => Url::to('/tasks/respond-ajax-validation'),
    ]); ?>
    <?= $form->field($respondModel, 'price', $fieldConfig)
        ->textInput(['class' => 'response-form-payment input input-middle input-money'])
        ->label(null, ['class' => 'form-modal-description']); ?>
    <?= $form->field($respondModel, 'text', $fieldConfig)
        ->textarea([
            'class' => 'input textarea',
            'rows' => 4,
            'placeholder' => 'Place your text',
        ])
        ->label(null, ['class' => 'form-modal-description']); ?>
    <?= Html::submitButton('Отправить', ['class' => 'button modal-button']); ?>
    <?php ActiveForm::end(); ?>
    <?= Html::button('Закрыть', ['class' => 'form-modal-close']); ?>
</section>
<section class="modal completion-form form-modal" id="complete-form">
    <h2>Завершение задания</h2>
    <p class="form-modal-description">Задание выполнено?</p>
    <?php
    $form
        = ActiveForm::begin(['action' => Url::to("/tasks/completion/{$task->id}")]);
    echo $form->field($taskCompletionModel, 'isCompletion')
        ->radioList([ $completionYes => 'Да', $completionDifficult => 'Возникли проблемы'],
            [
                'item' => function ($index, $label, $name, $checked, $value) {
                    return "<input class='visually-hidden completion-input completion-input--$value' type='radio' 
                    id='completion-radio--$value' name='$name' value='$value'>
                <label class='completion-label completion-label--$value' for='completion-radio--$value'>$label</label>";
                },
            ])->label(false);
    ?>
    <?= $form->field($taskCompletionModel, 'text', $fieldConfig)
        ->textarea([
            'class' => 'input textarea',
            'rows' => 4,
            'placeholder' => 'Place your text',
        ]); ?>
    <p class="form-modal-description">
        Оценка
    <div class="feedback-card__top--name completion-form-star">
        <span class="star-disabled"></span>
        <span class="star-disabled"></span>
        <span class="star-disabled"></span>
        <span class="star-disabled"></span>
        <span class="star-disabled"></span>
    </div>
    </p>
    <?php
    echo $form->field($taskCompletionModel, 'rating',
        ['template' => '{input}', 'options' => ['tag' => false]])
        ->hiddenInput(['id' => 'rating']);
    echo Html::submitButton('Отправить', ['class' => 'button modal-button']);
    ActiveForm::end();
    echo Html::button('Закрыть', ['class' => 'form-modal-close']);
    ?>
</section>
<section class="modal form-modal refusal-form" id="refuse-form">
    <h2>Отказ от задания</h2>
    <p>
        Вы собираетесь отказаться от выполнения задания.
        Это действие приведёт к снижению вашего рейтинга.
        Вы уверены?
    </p>
    <?php
    ActiveForm::begin([
        'action' => Url::to("/tasks/refusal/{$task->id}"),
        'enableClientValidation' => false,
    ]);
    echo Html::button('Отмена',
        ['class' => 'button__form-modal button', 'id' => 'close-modal']);
    echo Html::submitButton('Отказаться', [
        'class' => 'button__form-modal refusal-button button',
        'name' => 'refusal-btn',
        'value' => 'refusal-btn',
    ]);
    ActiveForm::end();
    echo Html::button('Закрыть', ['class' => 'form-modal-close']);
    ?>
</section>

<div class="overlay"></div>
