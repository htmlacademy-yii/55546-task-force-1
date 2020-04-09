<?php

namespace app\models;

use common\models\User;
use src\UserInitHelper\UserInitHelper;
use Yii;
use yii\authclient\clients\VKontakte;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Класс для работы с моделью пользователя VK
 *
 * Class Auth
 *
 * @package app\models
 */
class Auth extends ActiveRecord
{
    /**
     * Создание связи с пользователем
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Получение имени таблицы модели
     *
     * @return string имя таблицы модели
     */
    public static function tableName(): string
    {
        return 'auth';
    }

    /**
     * Получение списка правил валидации для модели
     *
     * @return array список правил валидации для модели
     */
    public function rules(): array
    {
        return [
            [['user_id', 'source', 'source_id'], 'required'],
            [['user_id', 'source_id'], 'integer'],
            [
                'user_id',
                'exist',
                'targetClass' => User::class,
                'targetAttribute' => 'id',
            ],
        ];
    }

    public static function onAuthVKontakte(VKontakte $client)
    {
        $clientId = $client->getId();
        $attributes = $client->getUserAttributes();
        if ($auth = self::findOne([
            'source' => $clientId,
            'source_id' => $attributes['id'],
        ])
        ) {
            return $auth->user;
        }

        $user = null;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user = (new UserInitHelper(new User([
                'login' => $attributes['first_name'].' '
                    .$attributes['last_name'],
                'email' => $attributes['email'],
                'password' => Yii::$app->security->generateRandomString(6),
                'city_id' => null,
                'role' => User::ROLE_CLIENT,
            ])))->initNotifications(new UserNotifications())
                ->initSetting(new UserSettings())
                ->initUserData(new UserData(['avatar' => $attributes['photo']]))
                ->user;
            (new self([
                'user_id' => $user->id,
                'source' => $clientId,
                'source_id' => $attributes['id'],
            ]))->save();

            $transaction->commit();
        } catch (\Exception $err) {
            $transaction->rollBack();
        }

        return $user;
    }
}
