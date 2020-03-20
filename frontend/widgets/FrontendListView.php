<?php
namespace frontend\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;

/**
 * Виджет для работы со списком моделей
 *
 * Class FrontendListView
 *
 * @package frontend\widgets
 */
class FrontendListView extends ListView
{
    /**
     * @return string|void
     */
    public function run()
    {
        if ($this->showOnEmpty || $this->dataProvider->getCount() > 0) {
            $content = preg_replace_callback('/{\\w+}/', function ($matches) {
                $content = $this->renderSection($matches[0]);
                return $content === false ? $matches[0] : $content;
            }, $this->layout);
        } else {
            $content = $this->options;
        }

        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'div');

        $content = Html::tag($tag, $content, $options);
        $content = Html::tag('div', $content, [
            'class' => 'new-task__wrapper'
        ]);
        $content .= $this->renderSection('{pager}');
        echo $content;
    }
}
