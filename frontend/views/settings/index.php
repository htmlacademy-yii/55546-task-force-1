<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$fieldConfig = ['template' => '{label}{input}{error}', 'options' => ['tag' => false]];

\frontend\assets\ProfileAsset::register($this);
?>

<section class="account__redaction-wrapper">
    <h1>Редактирование настроек профиля</h1>
    <?php $form = ActiveForm::begin(['enableClientValidation' => false, 'options' => ['enctype' => 'multipart/form-data']]); ?>
        <div class="account__redaction-section">
            <h3 class="div-line">Настройки аккаунта</h3>
            <div class="account__redaction-section-wrapper">
                <div class="account__redaction-avatar">
                    <img src="<?= $user->userData->getAvatar(); ?>" width="156" height="156">
                    <?= $form->field($model, 'avatar', ['template' => '{input}{label}{error}', 'options' => ['tag' => false]])
                        ->fileInput(['id' => 'upload-avatar'])->label(null, ['class' => 'link-regular']); ?>
                </div>
                <div class="account__redaction">
                    <div class="account__input account__input--name">
                        <?= $form->field($model, 'name', $fieldConfig)
                            ->textInput(['class' => 'input textarea', 'value' => $user->login]); ?>
                    </div>
                    <div class="account__input account__input--email" >
                        <?= $form->field($model, 'email', $fieldConfig)
                            ->textInput(['class' => 'input textarea', 'value' => $user->email]); ?>
                    </div>
                    <div class="account__input account__input--name">
                        <?= $form->field($model, 'cityId', $fieldConfig)
                            ->dropDownList($cities, [
                                'class' => 'multiple-select input multiple-select-big',
                                'size' => 1,
                                'options' => [$user->city_id => ['selected' => true]],
                            ]); ?>
                    </div>
                    <div class="account__input account__input--date">
                        <?= $form->field($model, 'birthday', $fieldConfig)->input('date', ['class' => 'input-middle input input-date', 'value' => $user->userData->birthday]); ?>
                    </div>
                    <div class="account__input account__input--info">
                        <?= $form->field($model, 'description', $fieldConfig)
                            ->textarea(['class' => 'input textarea', 'rows' => 7, 'value' => $user->userData->description]); ?>
                    </div>
                </div>
            </div>
            <h3 class="div-line">Выберите свои специализации</h3>
            <div class="account__redaction-section-wrapper">
                <?= $form->field($model, 'specializations')
                    ->checkboxList(yii\helpers\ArrayHelper::map($categories, 'id', 'title'), [
                        'item' => function ($_index, $label, $name, $checked, $id) use ($user) {
                            $checked = in_array($id, $user->specializationsId) ? 'checked' : '';
                            return "<input
                                class='visually-hidden checkbox__input'
                                type='checkbox'
                                name='$name'
                                id='specializations-$id'
                                value='$id'
                                $checked>
                                <label for='specializations-$id'>$label</label>";
                        },
                        'tag' => 'div',
                        'class' => 'search-task__categories account_checkbox--bottom'
                    ])->label(false); ?>
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

            <h3 class="div-line">Фото работ</h3>
            <div class="account__redaction-section-wrapper account__redaction">
                <span class="dropzone">Выбрать фотографии</span>
            </div>

            <h3 class="div-line">Контакты</h3>
            <div class="account__redaction-section-wrapper account__redaction">
                <div class="account__input">
                    <?= $form->field($model, 'phone', $fieldConfig)
                        ->input('tel', ['class' => 'input textarea', 'value' => $user->userData->phone]); ?>
                </div>
                <div class="account__input">
                    <?= $form->field($model, 'skype', $fieldConfig)
                        ->textInput(['class' => 'input textarea', 'value' => $user->userData->skype]); ?>
                </div>
                <div class="account__input" >
                    <?= $form->field($model, 'otherMessenger', $fieldConfig)
                        ->textInput(['class' => 'input textarea', 'value' => $user->userData->other_messenger]); ?>
                </div>
            </div>
            <h3 class="div-line">Настройки сайта</h3>
            <h4>Уведомления</h4>
            <div class="account__redaction-section-wrapper account_section--bottom">
                <div class="search-task__categories account_checkbox--bottom">
                    <?php $checkboxConfig = ['template' => '{input}{label}', 'options' => ['tag' => false]]; ?>
                    <?= $form->field($model, 'notifications[new-message]', $checkboxConfig)
                        ->checkbox(['class' => 'visually-hidden checkbox__input', 'value' => true, 'id' => 'notifications-1', 'checked' => (bool) $user->userNotifications->is_new_message], false)
                        ->label('Новое сообщение', ['for' => 'notifications-1']); ?>
                    <?= $form->field($model, 'notifications[task-actions]', $checkboxConfig)
                        ->checkbox(['class' => 'visually-hidden checkbox__input', 'value' => true, 'id' => 'notifications-2', 'checked' => (bool) $user->userNotifications->is_task_actions], false)
                        ->label('Действия по заданию', ['for' => 'notifications-2']); ?>
                    <?= $form->field($model, 'notifications[new-review]', $checkboxConfig)
                        ->checkbox(['class' => 'visually-hidden checkbox__input', 'value' => true, 'id' => 'notifications-3', 'checked' => (bool) $user->userNotifications->is_new_review], false)
                        ->label('Новый отзыв', ['for' => 'notifications-3']); ?>
                </div>

                <div class="search-task__categories account_checkbox account_checkbox--secrecy">
                    <?= $form->field($model, 'settings[show-only-client]', $checkboxConfig)
                        ->checkbox(['class' => 'visually-hidden checkbox__input', 'value' => true, 'id' => 'settings-1', 'checked' => (bool) $user->userSettings->is_hidden_contacts], false)
                        ->label('Показывать мои контакты только заказчику', ['for' => 'settings-1']); ?>
                    <?= $form->field($model, 'settings[hidden-profile]', $checkboxConfig)
                        ->checkbox(['class' => 'visually-hidden checkbox__input', 'value' => true, 'id' => 'settings-2', 'checked' => (bool) $user->userSettings->is_hidden_profile], false)
                        ->label('Не показывать мой профиль', ['for' => 'settings-2']); ?>
                </div>
            </div>
        </div>
        <?= Html::submitButton('Сохранить изменения', ['class' => 'button']); ?>
    <?php ActiveForm::end(); ?>
</section>
