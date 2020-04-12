<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\ServerErrorHttpException;

/**
 * Класс для работы с моделью файлов заданий
 *
 * Class TaskFile
 *
 * @package app\models
 */
class TaskFile extends ActiveRecord
{
    /** @var string строка с адресом директории для сохранения файла */
    public $path = '';

    /**
     * Сохранение списка файлов к заданию в нужную директорию
     *
     * @param int   $taskId числом с идентификатором задания
     * @param array $files  массив со списком файлов
     *
     * @throws ServerErrorHttpException
     * @throws \yii\db\Exception
     */
    public function setFiles(int $taskId, array $files): void
    {
        $data = [];
        foreach ($files as $file) {
            $fileName = $this->path.'/'.$file->baseName.'.'.$file->extension;
            if (!$file->saveAs($fileName)) {
                throw new ServerErrorHttpException('Не удалось сохранить файл');
            }
            $data[] = [$taskId, $fileName];
        }

        Yii::$app->db->createCommand()
            ->batchInsert(self::tableName(), ['task_id', 'file'], $data)
            ->execute();
    }

    /**
     * Получение имени таблицы модели
     *
     * @return string имя таблицы модели
     */
    public static function tableName(): string
    {
        return 'task_file';
    }

    /**
     * Получение списка правил валидации для модели
     *
     * @return array список правил валидации для модели
     */
    public function rules(): array
    {
        return [
            [['task_id', 'file'], 'required'],
            ['task_id', 'integer'],
            [
                'task_id',
                'exist',
                'targetClass' => Task::class,
                'targetAttribute' => 'id',
            ],
            ['file', 'string', 'max' => 255],
        ];
    }
}
