<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Регистрация аккаунта';
$this->params['breadcrumbs'][] = $this->title;
$fieldConfig = [
    'template' => "{label}\n{input}\n{error}",
    'options' => ['tag' => false],
];

?>
<section class="registration__user">
    <h1>Регистрация аккаунта</h1>
    <div class="registration-wrapper">
        <?php $form = ActiveForm::begin([
            'enableClientValidation' => false,
            'enableAjaxValidation' => false,
            'options' => ['class' => 'registration__user-form form-create'],
        ]) ?>
        <?= $form->field($model, 'email', $fieldConfig)
            ->textarea([
                'class' => 'input textarea '.($model->hasErrors('email')
                        ? 'field-danger' : ''),
                'rows' => 1,
            ])
            ->error(['class' => 'text-danger']); ?>
        <span>Введите валидный адрес электронной почты</span>

        <?= $form->field($model, 'login', $fieldConfig)
            ->textarea([
                'class' => 'input textarea '.($model->hasErrors('login')
                        ? 'field-danger' : ''),
                'rows' => 1,
            ])
            ->error(['class' => 'text-danger']); ?>
        <span>Введите ваше имя и фамилию</span>

        <?= $form->field($model, 'cityId', $fieldConfig)
            ->dropDownList($cities,
                [
                    'class' => 'multiple-select input town-select registration-town '
                        .($model->hasErrors('cityId') ? 'field-danger' : ''),
                ])
            ->error(['class' => 'text-danger']); ?>
        <span>Укажите город, чтобы находить подходящие задачи</span>

        <?= $form->field($model, 'password', $fieldConfig)
            ->passwordInput([
                'class' => 'input textarea '.($model->hasErrors('password')
                        ? 'field-danger' : ''),
            ])
            ->error(['class' => 'text-danger']); ?>
        <span>Длина пароля от 8 символов</span>

        <?= Html::submitButton('Cоздать аккаунт',
            ['class' => 'button button__registration']); ?>
        <?php ActiveForm::end() ?>
    </div>
</section>

