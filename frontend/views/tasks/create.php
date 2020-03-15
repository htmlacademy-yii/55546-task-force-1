<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$fieldConfig = ['template' => '{label}{input}{hint}{error}', 'options' => ['tag' => false]];
$labels = $model->attributeLabels();

\frontend\assets\TaskCreateAsset::register($this);
?>

<section class="create__task">
    <h1>Публикация нового задания</h1>
    <div class="create__task-main">
        <?php $form = ActiveForm::begin([
            'enableClientValidation' => false,
            'enableAjaxValidation' => false,
            'options' => [
                'class' => 'create__task-form form-create',
                'enctype' => 'multipart/form-data',
                'id' => 'task-form'
            ]
        ]);
        echo $form->field($model, 'title', $fieldConfig)
                ->textarea([
                    'class' => 'input textarea',
                    'rows' => 1,
                    'placeholder' => 'Повесить полку',
                ])->hint('<span>Кратко опишите суть работы</span>');
        echo $form->field($model, 'description', $fieldConfig)
            ->textarea([
                'class' => 'input textarea',
                'rows' => 7,
                'placeholder' => 'Place your text',
            ])->hint('<span>Укажите все пожелания и детали, чтобы исполнителям было проще соориентироваться</span>');
        echo $form->field($model, 'categoryId', $fieldConfig)
            ->dropDownList($categories, [
                'class' => 'multiple-select input multiple-select-big',
                'size' => 1
            ])->hint('<span>Выберите категорию</span>');
        ?>
            <label>Файлы</label>
            <span>Загрузите файлы, которые помогут исполнителю лучше выполнить или оценить работу</span>
            <div class="create__file">
                <span>Добавить новый файл</span>
<!--                <input type="file" name="files[]" class="dropzone">-->
            </div>
            <?= $form->field($model, 'location', $fieldConfig)
                ->input( 'search',[
                    'class' => 'input-navigation input-middle input',
                    'list' => 'cities-list',
                    'placeholder' => 'Санкт-Петербург, Калининский район',
                    'id' => 'autoComplete'
                ])->hint('<span>Укажите адрес исполнения, если задание требует присутствия</span>')?>
            <datalist id="cities-list"></datalist>

            <div class="create__price-time">
                <div class="create__price-time--wrapper">
                    <?= $form->field($model, 'price', $fieldConfig)
                        ->textarea([
                            'class' => 'input textarea input-money',
                            'rows' => 1,
                            'placeholder' => '1000',
                        ])->hint('<span>Не заполняйте для оценки исполнителем</span>')?>
                </div>
                <div class="create__price-time--wrapper">
                    <?= $form->field($model, 'dateEnd', $fieldConfig)
                        ->input('date', [
                            'class' => 'input-middle input input-date',
                            'placeholder' => '10.11, 15:00',
                        ])->hint('<span>Укажите крайний срок исполнения</span>')?>
                </div>
            </div>
        <?php ActiveForm::end() ?>
        <div class="create__warnings">
            <div class="warning-item warning-item--advice">
                <h2>Правила хорошего описания</h2>
                <h3>Подробности</h3>
                <p>Друзья, не используйте случайный<br>
                    контент – ни наш, ни чей-либо еще. Заполняйте свои
                    макеты, вайрфреймы, мокапы и прототипы реальным
                    содержимым.</p>
                <h3>Файлы</h3>
                <p>Если загружаете фотографии объекта, то убедитесь,
                    что всё в фокусе, а фото показывает объект со всех
                    ракурсов.</p>
            </div>
            <?php if (!empty($model->errors)): ?>
                <div class="warning-item warning-item--error">
                    <h2>Ошибки заполнения формы</h2>
                    <?php foreach ($model->errors as $label => $errors): ?>
                        <h3><?= $labels[$label]; ?></h3>
                        <?php foreach ($errors as $error): ?>
                            <p><?= $error; ?></p>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?= Html::submitButton('Опубликовать', ['form' => 'task-form', 'class' => 'button', 'id' => 'submit-btn']); ?>
</section>
