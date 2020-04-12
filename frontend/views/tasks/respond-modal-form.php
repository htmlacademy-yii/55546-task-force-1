<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$fieldConfig = ['template' => '<p>{label}{input}{error}</p>'];

?>

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
