<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "task_respond".
 *
 * @property int|null $task_id
 * @property int|null $user_id
 * @property string|null $text
 * @property string|null $public_date
 */
class TaskRespond extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_respond';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id', 'user_id'], 'integer'],
            [['text'], 'string'],
            [['public_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'task_id' => 'Task ID',
            'user_id' => 'User ID',
            'text' => 'Text',
            'public_date' => 'Public Date',
        ];
    }
}
