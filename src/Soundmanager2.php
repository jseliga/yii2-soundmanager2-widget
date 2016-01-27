<?php
/**
 * Soundmanager2 class for basic usage and creating widgets
 *
 * @package jseliga\soundmanager2
 * @since 0.1.0
 */

namespace jseliga\soundmanager2;


use jseliga\soundmanager2\assets\Soundmanager2Asset;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\AssetBundle;
use yii\web\JsExpression;
use yii\web\View;

/**
 * Base soundmanager2 class.
 * @since 0.1.0
 */
class Soundmanager2 extends Widget {

    /**
     * Path to swf script.
     * @var string
     * @since 0.1.0
     */
    const SWF_DIR = "/swf/";

    /**
     * Onready event.
     * @var string
     * @since 0.1.0
     */
    const EVENT_ONREADY = "onready";

    /**
     * Onload event.
     * @var string
     * @since 0.1.0
     */
    const EVENT_ONLOAD = "onload";

    /**
     * @var array Array of sounds.
     * @since 0.1.0
     */
    public $sounds = [];

    /**
     * @var array Global options for client script.
     * @since 0.1.0
     */
    public $clientOptions = [];

    /**
     * @var array Configuration for sounds.
     * @since 0.1.0
     */
    public $soundOptions = [];

    /**
     * @var AssetBundle Registered AssetBundle.
     * @since 0.1.0
     */
    protected $assetBundle;

    /**
     * @inheritdoc
     */
    public function init() {
        $this->registerAssets();
    }

    /**
     * @inheritdoc
     */
    public function run() {
        $this->registerClientScript();
    }

    /**
     * Returns URL of swf script if assetBundle is registered.
     * Otherwise returns null.
     * @return null|string
     * @since 0.1.0
     */
    public function getSwfUrl() {
        if ($this->assetBundle)  {
            return $this->assetBundle->baseUrl . self::SWF_DIR;
        }

        return null;
    }

    /**
     * Registers required assets.
     * @since 0.1.0
     */
    protected function registerAssets() {
        $this->assetBundle = Soundmanager2Asset::register($this->view);
    }

    /**
     * Registers client script.
     * @since 0.1.0
     */
    protected function registerClientScript() {
        $this->view->registerJs("soundManager.setup(" . Json::encode($this->getClientOptions()) . ");", View::POS_END);
    }

    /**
     * Merges client options with default values and returns it.
     * @return array merged client options
     * @since 0.1.0
     */
    protected function getClientOptions() {
        $options = $this->clientOptions;

        $options["url"] = $this->getSwfUrl();

        if (!ArrayHelper::keyExists(self::EVENT_ONREADY, $options)) {
            $options[self::EVENT_ONREADY] = $this->createSounds();
        }

        return array_diff_assoc($options, [
            "allowScriptAccess" => "always",
            "bgColor" => "#ffffff",
            "consoleOnly" => true,
            "debugMode" => true,
            "debugFlash" => false,
            "flashVersion" => 8,
            "flashPollingInterval" => null,
            "forceUseGlobalHTML5Audio" => true,
            "html5PollingInterval" => null,
            "html5Test" => "/^(probably|maybe)$/i",
            "flashLoadTimeout" => 1000,
            "idPrefix" => "sound",
            "ignoreMobileRestrictions" => false,
            "noSWFCache" => false,
            "preferFlash" => false,
            "useFlashBlock" => false,
            "useHighPerformance" => false,
            "useHTML5Audio" => true,
            "waitForWindowLoad" => false,
            "wmode" => null
        ]);
    }

    /**
     * Merges sound options with default sound options and returns it.
     * @param string $id id of sound
     * @return array merged sound options
     * @since 0.1.0
     */
    protected function getSoundOptions($id = null) {
        $options = $this->soundOptions;

        if ($id && ArrayHelper::keyExists($id, $this->sounds)) {
            $options = array_merge($options, $this->sounds[$id]);
            $options["id"] = $id;
        }

        return array_diff_assoc($options, [
            "autoLoad" => false,
            "autoPlay" => false,
            "from" => null,
            "loops" => 1,
            "multiShot" => true,
            "multiShotEvents" => false,
            "onid3" => null,
            "onload" => null,
            "onstop" => null,
            "onfinish" => null,
            "onresume" => null,
            "position" => null,
            "pan" => 0,
            "stream" => true,
            "to" => null,
            "type" => null,
            "usePolicyFile" => false,
            "volume" => 100,
            "whileloading" => null,
            "whileplaying" => null
        ]);
    }

    /**
     * Initializes sounds and returns it.
     * @return JsExpression initialized sounds
     * @since 0.1.0
     */
    protected function createSounds() {
        $sounds = "";

        foreach (array_keys($this->sounds) as $id) {
            $sounds .= "soundManager.createSound(" . Json::encode($this->getSoundOptions($id)) . ");";
        }

        return new JsExpression("function () {{$sounds}}");
    }
}
