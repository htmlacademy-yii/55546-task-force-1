<?php

namespace app\Action;

class ActionRespond
{
    public static function getInternalName()
    {
        return Action::ACTION_RESPOND;
    }

    public static function checkRight()
    {
        return true;
    }
}
