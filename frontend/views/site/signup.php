<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Регистрация аккаунта';
$this->params['breadcrumbs'][] = $this->title;
$fieldConfig = ['template' => "{label}\n{input}", 'options' => ['tag' => false]];
?>
<section class="registration__user">
    <h1>Регистрация аккаунта</h1>
    <div class="registration-wrapper">
        <?php $form = ActiveForm::begin(['options' => ['class' => 'registration__user-form form-create']]) ?>
            <?= $form->field($model, 'email', $fieldConfig)
                ->textarea(['class' => 'input textarea', 'rows' => 1, 'placeholder' => 'kumarm@mail.ru', 'value' => 'kumarm@mail.ru']); ?>
            <span>Введите валидный адрес электронной почты</span>

            <?= $form->field($model, 'login', $fieldConfig)
                ->textarea(['class' => 'input textarea', 'rows' => 1, 'placeholder' => 'Мамедов Кумар', 'value' => 'Мамедов Кумар']); ?>
            <span>Введите ваше имя и фамилию</span>

            <?= $form->field($model, 'city_id', $fieldConfig)
                ->dropDownList($cities, ['class' => 'multiple-select input town-select registration-town']); ?>
            <span>Укажите город, чтобы находить подходящие задачи</span>

            <?= $form->field($model, 'password', $fieldConfig)
                ->passwordInput(['class' => 'input textarea', 'value' => '123123123'])->label(null, ['class' => 'input-danger']); ?>
            <span>Длина пароля от 8 символов</span>

            <?= Html::submitButton('Cоздать аккаунт', ['class' => 'button button__registration']); ?>
        <?php ActiveForm::end() ?>
    </div>
</section>

