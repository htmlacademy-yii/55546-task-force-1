<?php
namespace common\models;

use app\models\Category;
use app\models\City;
use app\models\EventRibbon;
use app\models\FavoriteExecutor;
use app\models\Review;
use app\models\Task;
use app\models\UserData;
use app\models\UserNotifications;
use app\models\UserPhoto;
use app\models\UserSettings;
use app\models\UserSpecialization;
use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;


/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    const ROLE_CLIENT = 'client';
    const ROLE_EXECUTOR = 'executor';

    const SORT_TYPE_RATING = 'rating';
    const SORT_TYPE_ORDERS = 'orders';
    const SORT_TYPE_POPULARITY = 'popularity';

    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            ['login', 'trim'],
            ['login', 'required'],
            ['login', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public static function getCorrectAvatar($avatar)
    {
        if(!empty($avatar)) {
            return preg_match('/^http/', $avatar) ? $avatar : "/$avatar";
        }

        return '/img/user-photo.png';
    }

    public static function getUserUrl(int $id)
    {
        return "/users/view/$id";
    }

    public function getCurrentUserUrl()
    {
        return self::getUserUrl($this->id);
    }

    public function getEvents()
    {
        return $this->hasMany(EventRibbon::class, ['user_id' => 'id']);
    }

    public function getFavoriteExecutorsId()
    {
        return FavoriteExecutor::find()->select('executor_id')->where(['client_id' => $this->id])->column();
    }

    public function getReviewsCount()
    {
        return Review::find()->where(['executor_id' => $this->id])->count();
    }

    public function getOrdersCount()
    {
        return Task::find()->where(['executor_id' => $this->id])->count();
    }

    public function getUserData()
    {
        return $this->hasOne(UserData::class, ['user_id' => 'id']);
    }

    public function getUserNotifications()
    {
        return $this->hasOne(UserNotifications::class, ['user_id' => 'id']);
    }

    public function getUserSettings()
    {
        return $this->hasOne(UserSettings::class, ['user_id' => 'id']);
    }

    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    public function getSpecializationsId()
    {
        return UserSpecialization::find()->select('category_id')
            ->where(['user_id' => $this->id])->asArray()->column();
    }

    public function getSpecializations()
    {
        return Category::findAll($this->getSpecializationsId());
    }

    public function getTasks()
    {
        return $this->hasMany(Task::class, ['author_id' => 'id']);
    }

    public function getPhotos()
    {
        return $this->hasMany(UserPhoto::class, ['user_id' => 'id']);
    }

    public function getReviews()
    {
        return $this->hasMany(Review::class, ['executor_id' => 'id']);
    }

    public static function findIdentity($id)
    {
        return static::findOne((int) $id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
