<?php

namespace app\Action;

class ActionDenial extends Action
{
    public static function getInternalName(): string
    {
        return Action::ACTION_DENIAL;
    }

    public static function checkRight(): bool
    {
        return true;
    }
}
