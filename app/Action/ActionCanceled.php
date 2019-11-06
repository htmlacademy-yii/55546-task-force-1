<?php

namespace app\Action;

class ActionCanceled extends Action
{

    public static function getName()
    {
        return self::class;
    }

    public static function getInternalName()
    {
        return 'action_canceled';
    }

    public static function checkRight()
    {
        return true;
    }

}
