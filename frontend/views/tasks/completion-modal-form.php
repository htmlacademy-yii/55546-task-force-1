<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<section class="modal completion-form form-modal" id="complete-form">
    <h2>Завершение задания</h2>
    <p class="form-modal-description">Задание выполнено?</p>
    <?php
    $form
        = ActiveForm::begin(['action' => Url::to("/tasks/completion/{$task->id}")]);
    echo $form->field($taskCompletionModel, 'isCompletion')
        ->radioList([
            $completionYes => 'Да',
            $completionDifficult => 'Возникли проблемы',
        ],
            [
                'item' => function ($index, $label, $name, $checked, $value) {
                    return "<input class='visually-hidden completion-input completion-input--$value' type='radio' 
                    id='completion-radio--$value' name='$name' value='$value'>
                <label class='completion-label completion-label--$value' for='completion-radio--$value'>$label</label>";
                },
            ])->label(false);
    ?>
    <?= $form->field($taskCompletionModel, 'text',
        ['template' => '<p>{label}{input}{error}</p>'])
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
