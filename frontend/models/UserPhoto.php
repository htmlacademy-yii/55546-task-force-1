<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\validators\FileValidator;
use yii\web\ServerErrorHttpException;
use yii\web\UnsupportedMediaTypeHttpException;

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
    public $path = "";

    /**
     * Сохранение списка фотографий пользователя в нужную директорию
     *
     * @param array $files массив со списком файлов
     *
     * @throws ServerErrorHttpException
     * @throws UnsupportedMediaTypeHttpException
     * @throws \yii\db\Exception
     */
    public function setPhotos(array $files): void
    {
        if(count($files) > 6) {
            throw new UnsupportedMediaTypeHttpException('Не более 6 файлов');
        }

        $data = [];
        foreach ($files as $file) {
            if(!(new FileValidator(['skipOnEmpty' => false, 'extensions' => 'png, jpg']))->validate($file)) {
                throw new UnsupportedMediaTypeHttpException('Не допустимый формат файла');
            }
            $fileName = $this->path . '/' . $file->baseName . '.' . $file->extension;
            if(!$file->saveAs($fileName)) {
                throw new ServerErrorHttpException('Не удалось сохранить файл');
            }
            $data[] = [Yii::$app->user->identity->id, $fileName];
        }

        Yii::$app->db->createCommand()->batchInsert(self::tableName(), ['user_id', 'photo'], $data)->execute();
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
            [['user_id'], 'integer'],
            [['photo'], 'string', 'max' => 255],
        ];
    }
}
