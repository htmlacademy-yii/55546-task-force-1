<?php

namespace frontend\assets;

use frontend\widgets\assets\DropzoneAsset;
use yii\web\AssetBundle;

/**
 * Ассет для страницы c настройками пользователя
 *
 * Class ProfileAsset
 *
 * @package frontend\assets
 */
class ProfileAsset extends AssetBundle
{
    /** @var string строка с адресом базового пути */
    public $basePath = '@webroot';
    /** @var string строка с адресом базового пути */
    public $baseUrl = '@web';
    /** @var array массив со списком скриптов */
    public $js = ['js/profile-init.js'];

    public $depends
        = [
            DropzoneAsset::class,
        ];
}
