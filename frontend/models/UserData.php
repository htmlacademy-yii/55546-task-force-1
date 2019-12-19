<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_data".
 *
 * @property int|null $user_id
 * @property string|null $name
 * @property string|null $description
 * @property int|null $age
 * @property string|null $address
 * @property string|null $skype
 * @property string|null $phone
 * @property string|null $other_messenger
 * @property string|null $avatar
 * @property int|null $rating
 * @property int|null $views
 * @property int|null $order_count
 * @property string|null $status
 */
class UserData extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'age', 'rating', 'views', 'order_count'], 'integer'],
            [['description', 'address'], 'string'],
            [['name', 'skype', 'phone', 'other_messenger', 'avatar', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'name' => 'Name',
            'description' => 'Description',
            'age' => 'Age',
            'address' => 'Address',
            'skype' => 'Skype',
            'phone' => 'Phone',
            'other_messenger' => 'Other Messenger',
            'avatar' => 'Avatar',
            'rating' => 'Rating',
            'views' => 'Views',
            'order_count' => 'Order Count',
            'status' => 'Status',
        ];
    }
}