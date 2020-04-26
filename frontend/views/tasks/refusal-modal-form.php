<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>
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
        ['class' => 'button__form-modal button close-modal', 'id' => 'close-modal']);
    echo Html::submitButton('Отказаться', [
        'class' => 'button__form-modal refusal-button button',
        'name' => 'refusal-btn',
        'value' => 'refusal-btn',
    ]);
    ActiveForm::end();
    echo Html::button('Закрыть', ['class' => 'form-modal-close']);
    ?>
</section>
