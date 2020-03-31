<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Ассет для страницы просмотра задания
 *
 * Class TaskViewAsset
 *
 * @package frontend\assets
 */
class TaskViewAsset extends AssetBundle
{
    /** @var string строка с адресом базового пути */
    public $basePath = '@webroot';
    /** @var string строка с адресом базового пути */
    public $baseUrl = '@web';
    /** @var array массив со списком скриптов */
    public $js = [];

    /**
     * TaskViewAsset constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->js = [
            'js/main.js',
            'js/yandex-map.js',
            'js/messenger.js',
        ];

        parent::__construct($config);
    }
}
