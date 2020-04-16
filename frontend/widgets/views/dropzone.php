<?php

use frontend\widgets\assets\DropzoneAsset;

/** @var string $text */

DropzoneAsset::register($this);
?>
<span class="dropzone"><?= $text; ?></span>
