<?php

namespace app\models;

use Yii;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "chat".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $task_id
 */
class Chat extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'task_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'task_id' => 'Task ID',
        ];
    }

    public function getAllMessages(): array
    {
        return [];
    }

    public function addMessage(array $data): void
    {
        // todo add message;
    }
}
