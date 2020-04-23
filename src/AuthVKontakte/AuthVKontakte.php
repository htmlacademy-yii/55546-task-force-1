<?php

namespace src\AuthVKontakte;

use app\models\Auth;
use common\models\User;
use Yii;
use yii\authclient\clients\VKontakte;

/**
 * Класс для авторизации через социальную сеть VKontakte
 *
 * Class AuthVKontakte
 *
 * @package src\AuthVKontakte
 */
class AuthVKontakte
{
    /**
     * Метод для авторизации через социальную сеть VKontakte
     *
     * @param VKontakte $client пользовательские данные от VKontakte
     *
     * @return User|null объект пользователя
     */
    public static function auth(VKontakte $client): ?User
    {
        $clientId = $client->getId();
        $attributes = $client->getUserAttributes();
        if ($auth = Auth::findOne([
            'source' => $clientId,
            'source_id' => $attributes['id'],
        ])
        ) {
            return $auth->user;
        }

        return self::create($clientId, $attributes);
    }

    /**
     * В случае если пользователь ещё не заходил через VKontakte,
     * создать для него новую запись
     *
     * @param string $clientId   идентификатор клиента
     * @param array  $attributes пользовательские данные от VKontakte
     *
     * @return User|null объект пользователя
     */
    private static function create(string $clientId, array $attributes): ?User
    {
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
            (new Auth([
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
