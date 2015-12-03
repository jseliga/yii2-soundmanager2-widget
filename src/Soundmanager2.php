<?php

namespace jseliga\soundmanager2;


use jseliga\soundmanager2\assets\Soundmanager2Asset;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\AssetBundle;
use yii\web\JsExpression;
use yii\web\View;

class Soundmanager2 extends Widget {
    const SWF_DIR = "/swf/";

    const EVENT_ONREADY = "onready",
        EVENT_ONLOAD = "onload";

    public $sounds = [];

    public $clientOptions = [];

    public $soundOptions = [];

    /**
     * @var AssetBundle
     */
    protected $assetBundle;

    public function init() {
        $this->registerAssets();
    }

    public function run() {
        $this->registerClientScript();
    }

    public function getSwfPath() {
        if ($this->assetBundle)  {
            return $this->assetBundle->baseUrl . self::SWF_DIR;
        }

        return null;
    }

    protected function registerAssets() {
        $this->assetBundle = Soundmanager2Asset::register($this->view);
    }

    protected function registerClientScript() {
        $this->view->registerJs("soundManager.setup(" . Json::encode($this->getClientOptions()) . ");", View::POS_END);
    }

    protected function getClientOptions() {
        $options = $this->clientOptions;

        $options["url"] = $this->getSwfPath();

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

    protected function createSounds() {
        $sounds = "";

        foreach (array_keys($this->sounds) as $id) {
            $sounds .= "soundManager.createSound(" . Json::encode($this->getSoundOptions($id)) . ");";
        }

        return new JsExpression("function () {{$sounds}}");
    }
}
