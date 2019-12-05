<?php

namespace app\models;

use Yii;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property int|null $chat_id
 * @property int|null $author_id
 * @property string|null $public_date
 * @property string|null $text
 */
class Message extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chat_id', 'author_id'], 'integer'],
            [['public_date'], 'safe'],
            [['text'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'chat_id' => 'Chat ID',
            'author_id' => 'Author ID',
            'public_date' => 'Public Date',
            'text' => 'Text',
        ];
    }
}
