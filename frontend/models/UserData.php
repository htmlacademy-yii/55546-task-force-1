<?php
namespace app\models;

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
            [['description', 'age', 'address', 'skype', 'phone', 'other_messenger', 'avatar', 'rating', 'views', 'order_count'], 'safe'],
            [['user_id', 'age', 'rating', 'views', 'order_count'], 'integer'],
            [['description', 'address'], 'string'],
            [['skype', 'phone', 'other_messenger', 'avatar'], 'string', 'max' => 255],
        ];
    }

    public function getAvatar()
    {
        if(!empty($this->avatar)) {
            return preg_match('/^http/', $this->avatar) ? $this->avatar : "/$this->avatar";
        }

        return '/img/user-photo.png';
    }

    public function save($runValidation = true, $attributeNames = null): bool
    {
        if(!($result = parent::save($runValidation, $attributeNames))) {
            throw new \Exception();
        }
        return $result;
    }
}
