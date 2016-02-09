<?php
namespace dowlatow\widgets;

use dowlatow\helpers\ArrayHelper;
use yii\bootstrap\Alert;
use yii\bootstrap\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

class AlertsBlock extends Widget
{
    const ERROR   = 'error';
    const DANGER  = 'danger';
    const SUCCESS = 'success';
    const INFO    = 'info';
    const WARNING = 'warning';

    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - $key is the name of the session flash variable
     * - $value is the bootstrap alert type (i.e. danger, success, info, warning)
     */
    public $alertTypes = [
        self::ERROR   => 'alert-error',
        self::DANGER  => 'alert-danger',
        self::SUCCESS => 'alert-success',
        self::INFO    => 'alert-info',
        self::WARNING => 'alert-warning'
    ];

    /** @var array the options for rendering the close button tag. */
    public $closeButton = [];

    /** @var bool */
    public $onlyWrapper = false;

    /** @var bool */
    public $fillFlashes = true;

    /** @var int */
    public $duration = 10000;

    /** @var  array */
    private $flashes;

    /** @var int */
    private $alertCounter = 0;

    public function run()
    {
        echo Html::beginTag('div', ['id' => $this->options['id'], 'class' => $this->wrapperClass()]);
        if (!$this->onlyWrapper) {
            foreach ($this->renderItems($this->fillFlashes) as $item) {
                echo $item;
            }
        }
        echo Html::endTag('div');
        AlertsBlockAsset::register($this->getView());
        $this->getView()->registerJs('$(\'#' . $this->options['id'] . '\').alerts({duration: ' . $this->duration . '});');
    }

    private function fillFlashes()
    {
        $session = \Yii::$app->getSession();
        foreach ($this->alertTypes as $type => $class) {
            $this->flashes[$type] = ArrayHelper::merge($this->flashes[$type], $session->getFlash($type));
            $session->removeFlash($type);
        }
    }

    /**
     * @param bool $fillFlashes
     * @param bool $inFunction
     * @return string
     */
    public function generateJsAlerts($fillFlashes = true, $inFunction = false)
    {
        if ($fillFlashes) {
            $this->fillFlashes();
        }

        $command = '$(\'div.' . $this->wrapperClass() . '\').alerts(\'addAlerts\', ' . Json::htmlEncode($this->renderItems()) . ');';
        if ($inFunction) {
            $command = 'function(){' . $command . '}';
        }
        return $command;
    }

    /**
     * @param $type
     * @param $message
     */
    public function addAlert($type, $message)
    {
        $this->flashes[$type][] = $message;
    }

    /**
     * @param bool $fillFlashes
     * @return array
     */
    public function renderItems($fillFlashes = true)
    {
        if ($fillFlashes) {
            $this->fillFlashes();
        }
        $items = [];
        foreach ($this->flashes as $type => $data) {
            $data = (array)$data;
            foreach ($data as $i => $message) {
                $items[] = $this->renderItem($type, $message);
            }
        }
        $this->initFlashes();
        return $items;
    }

    /**
     * @param $type
     * @param $message
     * @return string
     * @throws \Exception
     */
    public function renderItem($type, $message)
    {
        $this->alertCounter += 1;

        /* initialize css class for each alert box */
        $options['class'] = $this->alertTypes[$type] . (isset($this->options['class']) ? ' ' . $this->options['class'] : '');
        /* assign unique id to each alert box */
        $options['id'] = $this->getId() . '-' . $type . '-' . $this->alertCounter;

        return Alert::widget([
            'body'        => $message,
            'closeButton' => $this->closeButton,
            'options'     => $options,
        ]);
    }

    private function initFlashes()
    {
        foreach ($this->alertTypes as $key => $item) {
            $this->flashes[$key] = [];
        }
    }

    /**
     * @return string
     */
    private function wrapperClass()
    {
        return 'alert-block';
    }

    public function init()
    {
        parent::init();
        $this->initFlashes();
    }
}