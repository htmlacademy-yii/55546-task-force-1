<?php

namespace frontend\widgets;

use yii\helpers\Html;
use yii\widgets\LinkPager;

/**
 * Виджет для работы с пагинацией
 *
 * Class FrontendPager
 *
 * @package frontend\widgets
 */
class FrontendPager extends LinkPager
{
    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @return string|void
     */
    public function run()
    {
        if ($this->registerLinkTags) {
            $this->registerLinkTags();
        }

        $content = $this->renderPageButtons();
        $content = Html::tag('div', $content, [
            'class' => 'new-task__pagination',
        ]);

        echo $content;
    }
}
