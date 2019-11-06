<?php

namespace app\Action;

abstract class Action
{

    abstract public static function getName();

    abstract public static function getInternalName();

    abstract public static function checkRight();

}
