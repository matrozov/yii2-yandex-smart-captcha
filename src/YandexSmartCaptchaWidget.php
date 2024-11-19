<?php

declare(strict_types=1);

namespace matrozov\yii2yandexSmartCaptcha;

use Yii;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\InputWidget;

/**
 * @property YandexSmartCaptcha $component
 *
 * @property string|null $hl
 * @property bool        $test
 * @property bool        $webView
 * @property bool        $invisible
 * @property string      $shieldPosition
 * @property bool        $hideShield
 */
class YandexSmartCaptchaWidget extends InputWidget
{
    public YandexSmartCaptcha|array|string $component = 'yandexSmartCaptcha';

    public string|null $hl = null;
    public bool        $test = false;
    public bool        $webView = false;
    public bool        $invisible = false;
    public string      $shieldPosition = 'top-left';
    public bool        $hideShield = false;

    protected const ALLOWED_LANGUAGES = ['ru', 'en', 'be', 'kk', 'tt', 'uk', 'uz', 'tr'];
    protected const ALLOWED_SHIELD_POSITIONS = ['top-left', 'center-left', 'bottom-left', 'top-right', 'center-right', 'bottom-right'];

    /**
     * @return void
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        parent::init();

        $this->component = Instance::ensure($this->component, YandexSmartCaptcha::class);

        if ($this->hl === null) {
            $this->hl = Yii::$app->language;
        }

        if (!in_array($this->hl, static::ALLOWED_LANGUAGES, true)) {
            throw new InvalidConfigException('Language must be one of ' . implode(', ', static::ALLOWED_LANGUAGES));
        }

        if (!in_array($this->shieldPosition, static::ALLOWED_SHIELD_POSITIONS, true)) {
            throw new InvalidConfigException('Shield position must be one of ' . implode(', ', static::ALLOWED_SHIELD_POSITIONS));
        }
    }

    /**
     * @return void
     * @throws InvalidConfigException
     */
    public function run(): void
    {
        $this->field->template = "{input}\n{error}";

        $identity = uniqid('yandex_smart_captcha_');

        $test = $this->test ? 'true' : 'false';
        $webView = $this->webView ? 'true' : 'false';
        $invisible = $this->invisible ? 'true' : 'false';
        $shieldPosition = $this->shieldPosition;
        $hideShield = $this->hideShield ? 'true' : 'false';

        if ($this->hasModel()) {
            $inputId = Html::getInputId($this->model, $this->attribute);

            echo Html::activeHiddenInput($this->model, $this->attribute, ['id' => $inputId]);
        } else {
            $inputId = Html::getInputIdByName($this->name);

            echo Html::hiddenInput($this->name, '', ['id' => $inputId]);
        }

        $this->view->registerJs(<<<JS
            function {$identity}() {
                if (!window.smartCaptcha) {
                    return;
                }
                
                const container = document.getElementById('{$identity}');
                
                window.smartCaptcha.render(container, {
                    sitekey: '{$this->component->clientKey}',
                    hl: '{$this->hl}',
                    test: {$test},
                    webView: {$webView},
                    invisible: {$invisible},
                    shieldPosition: '{$shieldPosition}',
                    hideShield: {$hideShield},
                    callback: function (token) {
                        document.getElementById('{$inputId}').value = token;
                    }
                });
            }
        JS, View::POS_HEAD);

        $this->view->registerJsFile('https://smartcaptcha.yandexcloud.net/captcha.js?render=onload&onload=' . $identity);

        echo Html::tag('div', '', ['id' => $identity]);
    }
}
