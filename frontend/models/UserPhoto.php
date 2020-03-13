<?php

namespace app\models;

use common\models\User;
use Yii;
use yii\db\ActiveRecord;
use yii\web\ServerErrorHttpException;
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

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'photo' => 'Photo',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function setPhoto(UploadedFile $file)
    {
        $fileName = $this->path . '/' . $file->baseName . '.' . $file->extension;
        if(!$file->saveAs($fileName)) {
            throw new ServerErrorHttpException('Не удалось сохранить файл');
        }
        $this->photo = $fileName;
    }
}
