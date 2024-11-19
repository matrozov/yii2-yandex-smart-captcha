<?php

declare(strict_types=1);

namespace matrozov\yii2yandexSmartCaptcha;

use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\httpclient\Exception;
use yii\validators\Validator;

/**
 * @property YandexSmartCaptcha $component
 *
 * @property string|bool $host
 * @property bool        $sendUserIp
 */
class YandexSmartCaptchaValidator extends Validator
{
    public YandexSmartCaptcha|array|string $component = 'yandexSmartCaptcha';

    public string|bool $host       = false;
    public bool        $sendUserIp = true;

    /**
     * @return void
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        parent::init();

        $this->component = Instance::ensure($this->component, YandexSmartCaptcha::class);
    }

    /**
     * @param $value
     * @return array|null
     * @throws \Exception
     */
    public function validateValue($value): array|null
    {
        if (empty($value)) {
            return [$this->message, []];
        }

        if (!$this->component->isValidToken($value, $this->host, $this->sendUserIp)) {
            return [$this->message, []];
        }

        return null;
    }
}
