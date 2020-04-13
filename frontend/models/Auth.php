<?php

namespace app\models;

use common\models\User;
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

    /**
     * Метод для авторизации через социальную сеть VKontakte
     *
     * @param VKontakte $client пользовательские данные от VKontakte
     *
     * @return User|null объект нового пользователя
     */
    public static function onAuthVKontakte(VKontakte $client): ?User
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
            $user = User::create([
                'login' => $attributes['first_name'].' '
                    .$attributes['last_name'],
                'email' => $attributes['email'],
                'password' => Yii::$app->getSecurity()->generatePasswordHash(
                    Yii::$app->security->generateRandomString(6)),
                'city_id' => null,
                'role' => User::ROLE_CLIENT,
            ], ['data' => ['avatar' => $attributes['photo']]]);
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
