<?php

namespace app\Action;

class ActionCompleted extends Action
{

    public static function getInternalName()
    {
        return Action::ACTION_COMPLETED;
    }

    public static function checkRight()
    {
        return true;
    }

}
