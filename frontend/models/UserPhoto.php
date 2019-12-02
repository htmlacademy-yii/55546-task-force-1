<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_photo".
 *
 * @property int|null $user_id
 * @property string|null $photo
 */
class UserPhoto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_photo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['photo'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'photo' => 'Photo',
        ];
    }
}
