<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\ServerErrorHttpException;

/**
 * This is the model class for table "task_file".
 *
 * @property int|null $task_id
 * @property string|null $file
 */
class TaskFile extends ActiveRecord
{
    public $path = '';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id'], 'integer'],
            [['file'], 'string', 'max' => 255],
        ];
    }

    public function setFiles(int $taskId, array $files)
    {
        $data = [];
        foreach ($files as $file) {
            $fileName = $this->path . '/' . $file->baseName . '.' . $file->extension;
            if(!$file->saveAs($fileName)) {
                throw new ServerErrorHttpException('Не удалось сохранить файл');
            }
            $data[] = [$taskId, $fileName];
        }

        Yii::$app->db->createCommand()->batchInsert(self::tableName(), ['task_id', 'file'], $data)->execute();
    }
}
