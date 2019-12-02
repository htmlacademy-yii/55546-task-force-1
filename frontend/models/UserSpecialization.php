<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_specialization".
 *
 * @property int|null $user_id
 * @property int|null $category_id
 */
class UserSpecialization extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_specialization';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'category_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'category_id' => 'Category ID',
        ];
    }
}
