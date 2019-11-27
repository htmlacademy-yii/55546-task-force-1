<?php

namespace frontend\components\Action;

class ActionCompleted extends Action
{
    public static function getInternalName(): string
    {
        return Action::ACTION_COMPLETED;
    }

    public static function checkRight(): bool
    {
        return true;
    }
}
