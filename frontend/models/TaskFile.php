<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task_file".
 *
 * @property int|null $task_id
 * @property string|null $file
 */
class TaskFile extends \yii\db\ActiveRecord
{
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

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'task_id' => 'Task ID',
            'file' => 'File',
        ];
    }
}
