<?php
namespace frontend\widgets;

use yii\helpers\Html;
use yii\widgets\LinkPager;

class FrontendPager extends LinkPager
{
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        if ($this->registerLinkTags) {
            $this->registerLinkTags();
        }

        $content = $this->renderPageButtons();
        $content = Html::tag('div', $content, [
            'class' => 'new-task__pagination'
        ]);

        echo $content;
    }
}
