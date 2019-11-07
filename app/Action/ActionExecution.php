<?php

namespace app\Action;

class ActionExecution extends Action
{

    public static function getInternalName()
    {
        return Action::ACTION_EXECUTION;
    }

    public static function checkRight()
    {
        return true;
    }

}
