<?php

namespace app\Action;

class ActionCompleted extends Action
{

    public static function getName()
    {
        return self::class;
    }

    public static function getInternalName()
    {
        return 'action_completed';
    }

    public static function checkRight()
    {
        return true;
    }

}
