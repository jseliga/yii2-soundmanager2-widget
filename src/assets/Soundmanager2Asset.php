<?php

namespace jseliga\soundmanager2\assets;

use yii\web\AssetBundle;

/**
 * Base assets for {@link jseliga\soundmanager2\Soundmanager2Widget}.
 * @package jseliga\soundmanager2\assets
 */
class Soundmanager2Asset extends AssetBundle {
    public $sourcePath = "@bower/soundmanager2";

    public $js = [
        "script/soundmanager2-min-js"
    ];
}
