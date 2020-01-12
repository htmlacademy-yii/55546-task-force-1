<?php

namespace frontend\components\DebugHelper;

class DebugHelper
{
    static public function debug($data) {
        echo "<pre>" . print_r($data, true) . "</pre>";
    }
}
