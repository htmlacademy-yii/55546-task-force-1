<?php
namespace app\models;

use yii\db\ActiveRecord;

class FavoriteExecutor extends ActiveRecord
{
    public static function tableName()
    {
        return 'favorite_executor';
    }
}
