<?php

namespace app\Action;

class ActionCanceled extends Action
{

    public static function getInternalName()
    {
        return Action::ACTION_CANCELED;
    }

    public static function checkRight()
    {
        return true;
    }

}
