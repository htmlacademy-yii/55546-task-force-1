<?php

namespace frontend\src\DebugHelper;

class DebugHelper
{
    static public function debug($data): string
    {
        echo "<pre>" . print_r($data, true) . "</pre>";
    }
}
