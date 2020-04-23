<?php

namespace src\EventRibbonHelper;

use app\models\EventRibbon;
use yii\helpers\ArrayHelper;

/**
 * Вспомогательный класс для работы с лентой событий
 *
 * Class EventRibbonHelper
 */
class EventRibbonHelper
{
    /**
     * Получение строки с описанием события в соответствии с его типом
     *
     * @param string $eventType строка с типом события
     *
     * @return string строка с описанием события
     */
    public static function getDescription(string $eventType): string
    {
        return ArrayHelper::getValue([
            EventRibbon::TYPE_NEW_TASK_RESPOND => 'Новый отклик к заданию',
            EventRibbon::TYPE_NEW_CHAT_MESSAGE => 'Новое сообщение в чате',
            EventRibbon::TYPE_TASK_DENIAL => 'Исполнитель отказался от задания',
            EventRibbon::TYPE_TASK_START => 'Ваш отклик был принят',
            EventRibbon::TYPE_TASK_COMPLETE => 'Завершено задание',
        ], $eventType, 'Не определённое действие');
    }

    /**
     * Получение строки CSS класса для формирования иконки соответствующей типу события
     *
     * @param string $eventType строка с типом события
     *
     * @return string строка CSS класса
     */
    public static function getIconClass(string $eventType): string
    {
        return ArrayHelper::getValue([
            EventRibbon::TYPE_NEW_CHAT_MESSAGE => 'lightbulb__new-task--message',
            EventRibbon::TYPE_TASK_COMPLETE => 'lightbulb__new-task--close',
        ], $eventType, 'lightbulb__new-task--executor');
    }
}
