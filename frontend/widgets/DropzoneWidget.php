<?php

namespace frontend\widgets;

use Yii;
use yii\widgets\InputWidget;

/**
 * Виджет для работы с плагином dropzone
 *
 * Class DropzoneWidget
 *
 * @package frontend\widgets
 */
class DropzoneWidget extends InputWidget
{
    /** @var string */
    public $text = '';

    /**
     * Executes the widget.
     *
     * @return string the result of widget execution to be outputted.
     */
    public function run()
    {
        if ($this->model->hasErrors('files')) {
            Yii::$app->session->set('filesErrors',
                $this->model->getErrors('files'));
        } elseif (Yii::$app->session->has('filesErrors')) {
            $this->model->addErrors([
                'files' => Yii::$app->session->get('filesErrors'),
            ]);
            Yii::$app->session->remove('filesErrors');
        }

        return $this->render('dropzone', ['text' => $this->text]);
    }
}
