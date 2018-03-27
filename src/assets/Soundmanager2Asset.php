<?php
/**
 * Base asset class for registering all required assets
 *
 * @package jseliga\soundmanager2\assets
 * @since 0.1.0
 */

namespace jseliga\soundmanager2\assets;

use yii\web\AssetBundle;

/**
 * Base assets for {@link jseliga\soundmanager2\Soundmanager2Widget}.
 * @since 0.1.0
 */
class Soundmanager2Asset extends AssetBundle {

    /**
     * @var string Source path of soundmanager2 assets.
     */
    public $sourcePath = "@npm/soundmanager2";

    /**
     * @var array Required javascript files.
     */
    public $js = [
        "script/soundmanager2-jsmin.js"
    ];
}
