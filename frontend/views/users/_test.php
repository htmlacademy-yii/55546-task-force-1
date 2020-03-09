<?php
use yii\db\Query;

$query = (new Query())->select([
    'user.id',
    'user.login',
    'user.last_activity',
    'user_data.avatar',
    'user_data.rating',
    'user_data.description',
    'CONCAT("[",GROUP_CONCAT(JSON_OBJECT("title", category.title, "id", category.id) SEPARATOR ","),"]") as categories'
])
    ->from('user')
    ->where([
        'user.role' => 'executor',
        'user.id' => 46
    ])
    ->leftJoin('user_data', 'user.id = user_data.user_id')
    ->leftJoin('user_specialization', 'user.id = user_specialization.user_id')
    ->leftJoin('category', 'user_specialization.category_id = category.id')
    ->groupBy([
        'user.id',
        'user_data.rating',
        'user_data.avatar',
        'user_data.description',
    ])->createCommand()->queryOne();
\frontend\components\DebugHelper\DebugHelper::debug($query);
