<?php

namespace app\models;

use common\models\User;
use yii\db\ActiveRecord;

/**
 * Класс для работы с моделью избранных исполнителей
 *
 * Class FavoriteExecutor
 *
 * @package app\models
 */
class FavoriteExecutor extends ActiveRecord
{
    /**
     * Получение имени таблицы модели
     *
     * @return string имя таблицы модели
     */
    public static function tableName(): string
    {
        return 'favorite_executor';
    }

    /**
     * Получение списка правил валидации для модели
     *
     * @return array список правил валидации для модели
     */
    public function rules(): array
    {
        return [
            [['executor_id', 'client_id'], 'required'],
            [['executor_id', 'client_id'], 'integer'],
            [
                ['executor_id', 'client_id'],
                'exist',
                'targetClass' => User::class,
                'targetAttribute' => 'id',
            ],
        ];
    }

    /**
     * Метод для переключения добавления/удаления исполнителя
     * в избранное к указанному клиенту
     *
     * @param int $clientId число идентификатор клиента
     * @param int $executorId число идентификатор исполнителя
     *
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function toggleUserFavorite(
        int $clientId,
        int $executorId
    ): void {
        $params = ['client_id' => $clientId, 'executor_id' => $executorId];

        if ($data = self::findOne($params)) {
            $data->delete();
        } else {
            (new self($params))->save();
        }
    }
}
