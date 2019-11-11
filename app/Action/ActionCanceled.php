<?php

namespace app\Action;

class ActionCanceled extends Action
{
    public static function getInternalName(): string
    {
        return Action::ACTION_CANCELED;
    }

    public static function checkRight(): bool
    {
        return true;
    }
}
