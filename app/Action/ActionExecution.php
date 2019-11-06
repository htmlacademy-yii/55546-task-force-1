<?php

namespace app\Action;

class ActionExecution extends Action
{

    public static function getName()
    {
        return self::class;
    }

    public static function getInternalName()
    {
        return 'action_execution';
    }

    public static function checkRight()
    {
        return true;
    }

}
