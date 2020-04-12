<?php

namespace app\models;

use common\models\User;
use Yii;
use yii\db\ActiveRecord;
use yii\web\ServerErrorHttpException;

/**
 * Класс для работы с моделью фотографий пользователя
 *
 * Class UserPhoto
 *
 * @package app\models
 */
class UserPhoto extends ActiveRecord
{
    /** @var string строка с адресом директории для сохранения фотографии */
    public $path = '';

    /**
     * Сохранение списка фотографий пользователя в нужную директорию
     *
     * @param array $files массив со списком файлов
     *
     * @throws ServerErrorHttpException
     * @throws \yii\db\Exception
     */
    public function setPhotos(array $files): void
    {
        $data = [];
        foreach ($files as $file) {
            $fileName = $this->path.'/'.$file->baseName.'.'.$file->extension;
            if (!$file->saveAs($fileName)) {
                throw new ServerErrorHttpException('Не удалось сохранить файл');
            }
            $data[] = [Yii::$app->user->identity->id, $fileName];
        }

        Yii::$app->db->createCommand()
            ->batchInsert(self::tableName(), ['user_id', 'photo'], $data)
            ->execute();
    }

    /**
     * Получение имени таблицы модели
     *
     * @return string имя таблицы модели
     */
    public static function tableName(): string
    {
        return 'user_photo';
    }

    /**
     * Получение списка правил валидации для модели
     *
     * @return array список правил валидации для модели
     */
    public function rules(): array
    {
        return [
            [['user_id', 'photo'], 'required'],
            ['user_id', 'integer'],
            [
                'user_id',
                'exist',
                'targetClass' => User::class,
                'targetAttribute' => 'id',
            ],
            ['photo', 'string', 'max' => 255],
        ];
    }
}
