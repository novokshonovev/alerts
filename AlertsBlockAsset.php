<?php
namespace dowlatow\widgets;


use dowlatow\assets\WidgetGeneratorAsset;
use yii\bootstrap\BootstrapAsset;
use yii\web\AssetBundle;

class AlertsBlockAsset extends AssetBundle
{
    public $js = [
        'js' . DIRECTORY_SEPARATOR . 'alerts.js',
    ];

    public $css = [];

    public function init()
    {
        parent::init();

        $this->sourcePath = __DIR__ . DIRECTORY_SEPARATOR . 'source';
        $this->depends[]  = WidgetGeneratorAsset::className();
        $this->depends[]  = BootstrapAsset::className();
    }
}