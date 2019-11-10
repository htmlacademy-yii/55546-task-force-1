<?php

namespace app\Action;

class ActionDenial extends Action
{
    public static function getInternalName()
    {
        return Action::ACTION_DENIAL;
    }

    public static function checkRight()
    {
        return true;
    }
}
