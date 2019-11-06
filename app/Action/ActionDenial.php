<?php

namespace app\Action;

class ActionDenial extends Action
{

    public static function getName()
    {
        return self::class;
    }

    public static function getInternalName()
    {
        return 'action_denial';
    }

    public static function checkRight()
    {
        return true;
    }

}
