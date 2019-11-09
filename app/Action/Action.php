<?php

namespace app\Action;

abstract class Action
{

    const ACTION_COMPLETED = 'action_completed'; // выполнить
    const ACTION_DENIAL = 'action_denial'; // отказаться
    const ACTION_CANCELED = 'action_canceled'; // отменить
    const ACTION_RESPOND = 'action_respond'; // откликнуться

    public static function getName()
    {
        return static::class;
    }

    abstract public static function getInternalName();

    abstract public static function checkRight();

}
