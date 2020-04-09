<?php

use frontend\assets\TaskViewAsset;
use frontend\assets\YandexMapAsset;
use yii\helpers\Html;
use app\models\TaskRespond;
use app\models\Task;
use yii\helpers\Url;

$this->title = "Задание: $task->title";

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
                        <?php if ($task->getLocation()): ?>
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
            if ($isAuthor && $task->status === Task::STATUS_EXECUTION) {
                echo Html::button('Завершить', [
                    'class' => 'button button__big-color request-button open-modal',
                    'data-for' => 'complete-form',
                ]);
            }
            if ($isAuthor && $task->status === Task::STATUS_NEW) {
                echo Html::a('Отмена', "/tasks/cancel/$task->id", [
                    'class' => 'button button__big-color refusal-button open-modal',
                    'data-for' => 'canceled-form',
                ]);
            }
            if ($isExecutor && $isRespond && $isSelectedExecutor) {
                echo Html::button('Отказаться', [
                    'class' => 'button button__big-color refusal-button open-modal',
                    'data-for' => 'refuse-form',
                ]);
            }
            if ($isExecutor && !$isRespond) {
                echo Html::button('Откликнуться', [
                    'class' => 'button button__big-color response-button open-modal',
                    'data-for' => 'response-form',
                ]);
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
                                        <span <?= ($respond->user->rating
                                            >= $i) ? ''
                                            : 'class="star-disabled"'; ?>></span>
                                    <?php endfor; ?>
                                    <b><?= $respond->user->rating; ?></b>
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
    <?= $this->render('user', [
        'user' => $isAuthor && $task->executor ? $task->executor : $task->author,
        'isShowExecutor' => $isAuthor && $task->executor,
    ]);
    ?>

    <?php if ($task->executor_id && ($isAuthor || $isSelectedExecutor)): ?>
        <div id="chat-container">
            <chat class="connect-desk__chat" task="<?= $task->id; ?>"></chat>
        </div>
    <?php endif; ?>
</section>

<?= $this->render('respond-modal-form', [
    'task' => $task,
    'respondModel' => $respondModel,
]); ?>
<?= $this->render('completion-modal-form', [
    'task' => $task,
    'taskCompletionModel' => $taskCompletionModel,
    'completionYes' => $completionYes,
    'completionDifficult' => $completionDifficult,
]); ?>
<?= $this->render('refusal-modal-form', ['task' => $task]); ?>

<div class="overlay"></div>
