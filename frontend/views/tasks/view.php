<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\TaskRespond;
use app\models\Task;
use yii\helpers\Url;

$this->title = "Задание: $task->title";

$fieldConfig = ['template' => '<p>{label}{input}{error}</p>'];
$respondsCount = count($task->responds);

\frontend\assets\TaskViewAsset::register($this);

?>

<script>
    // временный скрипт для проверки ajax запроса
    (async () => console.log(await fetch(`/api/messages/${1}`).then(res => res.text()))) ();
</script>

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
                    <div class="content-view__map" id="map" style="width: 361px; height: 292px;">
                        <?php if($task->latitude && $task->longitude): ?>
                            <?= Html::hiddenInput('location-position', "$task->latitude $task->longitude"); ?>
                        <?php else: ?>
                            <a href="#"><img src="/img/map.jpg" width="361" height="292"
                                             alt="Москва, Новый арбат, 23 к. 1"></a>
                        <?php endif; ?>
                    </div>
                    <div class="content-view__address">
                        <?php if($taskLocation): ?>
                            <span class="address__town">
                                <?= $task->location->AddressLine; ?>
                            </span>
                            <br>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-view__action-buttons">
            <?php
                if($isAuthor) {
                    if($task->status === Task::STATUS_EXECUTION) {
                        echo Html::button('Завершить', [
                            'class' => 'button button__big-color request-button open-modal',
                            'data-for' => 'complete-form'
                        ]);
                    }
                } else if($isExecutor) {
                    if($isRespond) {
                        echo Html::button('Отказаться', [
                            'class' => 'button button__big-color refusal-button open-modal',
                            'data-for' => 'refuse-form'
                        ]);
                    } else {
                        echo Html::button('Откликнуться', [
                            'class' => 'button button__big-color response-button open-modal',
                            'data-for' => 'response-form'
                        ]);
                    }
                }
            ?>
        </div>
    </div>
    <?php if($respondsCount > 0): ?>
        <div class="content-view__feedback">
            <h2>Отклики <span>(<?= $respondsCount; ?>)</span></h2>
            <div class="content-view__feedback-wrapper">
                <?php foreach ($task->responds as $respond): ?>
                    <?php if($isAuthor || Yii::$app->user->identity->id === $respond->user_id): ?>
                        <div class="content-view__feedback-card">
                            <div class="feedback-card__top">
                                <a href="#"><img src="/img/man-glasses.jpg" width="55" height="55"></a>
                                <div class="feedback-card__top--name">
                                    <p><a href="#" class="link-regular"><?= $respond->user->login; ?></a></p>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span <?= ($respond->user->userData->rating >= $i) ? '' : 'class="star-disabled"'; ?>></span>
                                    <?php endfor; ?>
                                    <b><?= $respond->user->userData->rating; ?></b>
                                </div>
                                <span class="new-task__time"><?= $respond->public_date; ?> назад</span>
                            </div>
                            <div class="feedback-card__content">
                                <p><?= $respond->text; ?></p>
                                <span><?= $respond->price; ?> ₽</span>
                            </div>
                            <div class="feedback-card__actions">
                                <?php
                                if($isAuthor && $respond->status === TaskRespond::STATUS_NEW && $task->status === Task::STATUS_NEW) {
                                    echo Html::a('Подтвердить', Url::to("/tasks/decision/accepted/$respond->id/$task->id"), [
                                        'class' => 'button__small-color request-button button'
                                    ]);
                                    echo Html::a('Отказать', Url::to("/tasks/decision/denied/$respond->id/$task->id"), [
                                        'class' => 'button__small-color refusal-button button'
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
    <div class="connect-desk__profile-mini">
        <div class="profile-mini__wrapper">
            <h3>Заказчик</h3>
            <div class="profile-mini__top">
                <img src="/img/man-brune.jpg" width="62" height="62" alt="Аватар заказчика">
                <div class="profile-mini__name five-stars__rate">
                    <p><?= $task->author->login; ?></p>
                </div>
            </div>
            <p class="info-customer">
                <span><?= count($task->author->tasks); ?> заданий</span>
                <span class="last-"><?= $task->author->date_registration; ?> на сайте</span>
            </p>
            <a href="#" class="link-regular">Смотреть профиль</a>
        </div>
    </div>
    <div id="chat-container">
        <!--                    добавьте сюда атрибут task с указанием в нем id текущего задания-->
        <chat class="connect-desk__chat" task="<?= $task->id; ?>"></chat>
    </div>
</section>

<section class="modal response-form form-modal" id="response-form">
    <h2>Отклик на задание</h2>
    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'enableAjaxValidation' => true,
        'validationUrl' => Url::to('/tasks/respond-ajax-validation')
    ]); ?>
        <?= $form->field($respondModel, 'price', $fieldConfig)
            ->textInput(['class' => 'response-form-payment input input-middle input-money'])
            ->label(null, ['class' => 'form-modal-description']); ?>
        <?= $form->field($respondModel, 'text', $fieldConfig)
            ->textarea(['class' => 'input textarea', 'rows' => 4, 'placeholder' => 'Place your text'])
            ->label(null, ['class' => 'form-modal-description']); ?>
        <?= Html::submitButton('Отправить', ['class' => 'button modal-button']); ?>
    <?php ActiveForm::end(); ?>
    <?= Html::button('Закрыть', ['class' => 'form-modal-close']); ?>
</section>
<section class="modal completion-form form-modal" id="complete-form">
    <h2>Завершение задания</h2>
    <p class="form-modal-description">Задание выполнено?</p>
    <?php
    $form = ActiveForm::begin();
    echo $form->field($taskCompletionModel, 'isCompletion')->radioList(['yes' => 'Да', 'difficult' => 'Возникли проблемы'],
        [
            'item' => function ($index, $label, $name, $checked, $value) {
                return "<input class=\"visually-hidden completion-input completion-input--$value\" type=\"radio\" 
                    id=\"completion-radio--$value\" name=\"$name\" value=\"$value\">
                <label class=\"completion-label completion-label--$value\" for=\"completion-radio--$value\">$label</label>";
            }
        ])->label(false);
    ?>
        <?= $form->field($taskCompletionModel, 'text', $fieldConfig)
            ->textarea(['class' => 'input textarea', 'rows' => 4, 'placeholder' => 'Place your text']); ?>
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
    echo $form->field($taskCompletionModel,'rating', ['template' => '{input}', 'options' => ['tag' => false]])
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
        ActiveForm::begin(['enableClientValidation' => false]);
        echo Html::button('Отмена', ['class' => 'button__form-modal button', 'id' => 'close-modal']);
        echo Html::submitButton('Отказаться', [
            'class' => 'button__form-modal refusal-button button',
            'name' => 'refusal-btn',
            'value' => 'refusal-btn'
        ]);
        ActiveForm::end();
        echo Html::button('Закрыть', ['class' => 'form-modal-close']);
    ?>
</section>

<div class="overlay"></div>
