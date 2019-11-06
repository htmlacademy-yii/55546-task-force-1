<?php

namespace app\Action;

class ActionRespond
{

    public static function getName()
    {
        return self::class;
    }

    public static function getInternalName()
    {
        return 'action_respond';
    }

    public static function checkRight()
    {
        return true;
    }

}
