<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$fieldConfig = ['template' => '{label}{input}{error}', 'options' => ['tag' => false]];
?>

<section class="account__redaction-wrapper">
    <h1>Редактирование настроек профиля</h1>
    <?php $form = ActiveForm::begin(); ?>
        <div class="account__redaction-section">
            <h3 class="div-line">Настройки аккаунта</h3>
            <div class="account__redaction-section-wrapper">
                <div class="account__redaction-avatar">
                    <img src="/img/man-glasses.jpg" width="156" height="156">
                    <?= $form->field($model, 'avatar', ['template' => '{input}{label}{error}', 'options' => ['tag' => false]])
                        ->fileInput(['id' => 'upload-avatar'])->label(null, ['class' => 'link-regular']); ?>
                </div>
                <div class="account__redaction">
                    <div class="account__input account__input--name">
                        <?= $form->field($model, 'name', $fieldConfig)->textInput(['class' => 'input textarea', 'disabled']); ?>
                    </div>
                    <div class="account__input account__input--email" >
                        <?= $form->field($model, 'email', $fieldConfig)->textInput(['class' => 'input textarea']); ?>
                    </div>
                    <div class="account__input account__input--name">
                        <?= $form->field($model, 'city', $fieldConfig)
                            ->dropDownList(['Moscow' => 'Москва', 'SPB' => 'Санкт-Петербург', 'Krasnodar' => 'Краснодар', 'Irkutsk' => 'Иркутск', 'Vladivostok' => 'Владивосток'],
                                ['class' => 'multiple-select input multiple-select-big', 'size' => 1]); ?>
                    </div>
                    <div class="account__input account__input--date">
                        <?= $form->field($model, 'birthday', $fieldConfig)->input('date', ['class' => 'input-middle input input-date']); ?>
                    </div>
                    <div class="account__input account__input--info">
                        <?= $form->field($model, 'description', $fieldConfig)->textarea(['class' => 'input textarea', 'rows' => 7]); ?>
                    </div>
                </div>
            </div>
            <h3 class="div-line">Выберите свои специализации</h3>
            <div class="account__redaction-section-wrapper">
                <div class="search-task__categories account_checkbox--bottom">
                    <?= $form->field($model, 'specializations', ['options' => ['tag' => false]])
                        ->checkboxList(yii\helpers\ArrayHelper::map($categories, 'id', 'title'), [
                            'item' => function ($_index, $label, $name, $checked, $id) {
                                $checked = $checked ? "checked" : "";
                                return "<input
                                    class='visually-hidden checkbox__input'
                                    type='checkbox'
                                    name='$name'
                                    id='specializations-$id'
                                    value='$id'
                                    $checked>
                                    <label for='specializations-$id'>$label</label>";
                            }
                        ])->label(false); ?>

                   <!-- <input class='visually-hidden checkbox__input' id='205' type='checkbox' name='' value='' checked>
                    <label for='205'>Курьерские услуги</label>
                    <input class="visually-hidden checkbox__input" id="206" type="checkbox" name="" value="" checked>
                    <label  for="206">Грузоперевозки</label>
                    <input class="visually-hidden checkbox__input" id="207" type="checkbox" name="" value="">
                    <label for="207">Перевод текстов</label>
                    <input class="visually-hidden checkbox__input" id="208" type="checkbox" name="" value="" checked>
                    <label for="208">Ремонт транспорта</label>
                    <input class="visually-hidden checkbox__input" id="209" type="checkbox" name="" value="">
                    <label  for="209">Удалённая помощь</label>
                    <input class="visually-hidden checkbox__input" id="210" type="checkbox" name="" value="">
                    <label  for="210">Выезд на стрелку</label> -->
                </div>
            </div>
            <h3 class="div-line">Безопасность</h3>
            <div class="account__redaction-section-wrapper account__redaction">
                <div class="account__input">
                    <?= $form->field($model, 'password', $fieldConfig)->input('password', ['class' => 'input textarea']); ?>
                </div>
                <div class="account__input">
                    <?= $form->field($model, 'copyPassword', $fieldConfig)->input('password', ['class' => 'input textarea']); ?>
                </div>
            </div>
            <h3 class="div-line">Контакты</h3>
            <div class="account__redaction-section-wrapper account__redaction">
                <div class="account__input">
                    <?= $form->field($model, 'phone', $fieldConfig)->input('tel', ['class' => 'input textarea']); ?>
                </div>
                <div class="account__input">
                    <?= $form->field($model, 'skype', $fieldConfig)->textInput(['class' => 'input textarea']); ?>
                </div>
                <div class="account__input" >
                    <?= $form->field($model, 'otherMessenger', $fieldConfig)->textInput(['class' => 'input textarea']); ?>
                </div>
            </div>
            <h3 class="div-line">Настройки сайта</h3>
            <h4>Уведомления</h4>
            <div class="account__redaction-section-wrapper account_section--bottom">
                <div class="search-task__categories account_checkbox--bottom">
                    <?php $checkboxConfig = ['template' => '{input}{label}', 'options' => ['tag' => false]]; ?>
                    <?= $form->field($model, 'notifications[]', $checkboxConfig)
                        ->checkbox(['class' => 'visually-hidden checkbox__input', 'value' => 'new-message', 'id' => 'notifications-1'], false)
                        ->label('Новое сообщение', ['for' => 'notifications-1']); ?>
                    <?= $form->field($model, 'notifications[]', $checkboxConfig)
                        ->checkbox(['class' => 'visually-hidden checkbox__input', 'value' => 'task-actions', 'id' => 'notifications-2'], false)
                        ->label('Действия по заданию', ['for' => 'notifications-2']); ?>
                    <?= $form->field($model, 'notifications[]', $checkboxConfig)
                        ->checkbox(['class' => 'visually-hidden checkbox__input', 'value' => 'new-review', 'id' => 'notifications-3'], false)
                        ->label('Новый отзыв', ['for' => 'notifications-3']); ?>
                </div>
                <div class="search-task__categories account_checkbox account_checkbox--secrecy">
                    <?= $form->field($model, 'notifications[]', $checkboxConfig)
                        ->checkbox(['class' => 'visually-hidden checkbox__input', 'value' => 'show-only-client', 'id' => 'notifications-4'], false)
                        ->label('Показывать мои контакты только заказчику', ['for' => 'notifications-4']); ?>
                    <?= $form->field($model, 'notifications[]', $checkboxConfig)
                        ->checkbox(['class' => 'visually-hidden checkbox__input', 'value' => 'hidden-profile', 'id' => 'notifications-5'], false)
                        ->label('Не показывать мой профиль', ['for' => 'notifications-5']); ?>
                </div>
            </div>
        </div>
        <?= Html::submitButton('Сохранить изменения', ['class' => 'button']); ?>
    <?php ActiveForm::end(); ?>
</section>
