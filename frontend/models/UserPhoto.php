<?php

namespace app\models;

use common\models\User;
use Yii;
use yii\db\ActiveRecord;
use yii\validators\FileValidator;
use yii\web\ServerErrorHttpException;
use yii\web\UnsupportedMediaTypeHttpException;
use yii\web\UploadedFile;

/**
 * This is the model class for table "user_photo".
 *
 * @property int|null $user_id
 * @property string|null $photo
 */
class UserPhoto extends ActiveRecord
{
    public $path = "";

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

    public function setPhotos(array $files)
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
}
