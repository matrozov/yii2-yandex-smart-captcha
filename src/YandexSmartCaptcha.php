<?php

declare(strict_types=1);

namespace matrozov\yii2yandexSmartCaptcha;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;

/**
 * @property string $clientKey
 * @property string $secretKey
 *
 * @property-read Client $client
 * @see self::getClient()
 */
class YandexSmartCaptcha extends Component
{
    public string $clientKey = '';
    public string $serverKey = '';

    protected Client|null $_client = null;

    /**
     * @return void
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        parent::init();

        if (empty($this->clientKey)) {
            throw new InvalidConfigException('clientKey must be set.');
        }

        if (empty($this->serverKey)) {
            throw new InvalidConfigException('serverKey must be set.');
        }
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        if (!$this->_client) {
            $this->_client = new Client();
        }

        return $this->_client;
    }

    /**
     * @param string $token
     * @param string|bool $host
     * @param bool $sendUserIp
     * @return bool
     */
    public function isValidToken(string $token, string|bool $host, bool $sendUserIp): bool
    {
        $data = [
            'secret' => $this->serverKey,
            'token'  => $token,
        ];

        if ($sendUserIp) {
            $data['ip'] = Yii::$app->request->userIP;
        }

        $response = $this->client
            ->post('https://smartcaptcha.yandexcloud.net/validate', $data)
            ->setFormat(Client::FORMAT_URLENCODED)
            ->send();

        if (!$response->isOk) {
            /**
             * To avoid delays in user request processing, we recommend processing HTTP errors
             * (response codes other than 200) as the "status": "ok" service response.
             * https://yandex.cloud/en/docs/smartcaptcha/concepts/validation
             */
            return true;
        }

        if (ArrayHelper::getValue($response->data, 'status') !== 'ok') {
            return false;
        }

        if (($host === true) || (is_string($host) && !empty($host))) {
            $host = ($host === true) ? Yii::$app->request->hostName : $host;

            if (ArrayHelper::getValue($response->data, 'host') !== $host) {
                return false;
            }
        }

        return true;
    }
}
