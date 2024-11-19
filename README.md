[![Total Downloads](https://img.shields.io/packagist/dt/matrozov/yii2-yandex-smart-captcha.svg?style=flat-square)](https://packagist.org/packages/matrozov/yii2-yandex-smart-captcha)

# Yii2 Yandex Smart Captcha

Adds [Yandex Smart Captcha](https://yandex.cloud/en/services/smartcaptcha) into yii2 project

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist matrozov/yii2-yandex-smart-captcha "*"
```

or add

```
"matrozov/yii2-yandex-smart-captcha": "*"
```

to the require section of your `composer.json` file.

## Usage

Once the extension is installed, simply use it in your code by:

add this to your components main.php

```php
'components' => [
    ...
    'yandexSmartCaptcha' => [
        'class'     => 'matrozov\yii2yandexSmartCaptcha\YandexSmartCaptcha',
        'serverKey' => 'ysc2_********',
        'clientKey' => 'ysc1_********',
    ],
```

and in your model

```php
public $yandexSmartCaptcha;

public function rules()
{
    return [
        ...
        [['yandexSmartCaptcha'], \matrozov\yii2yandexSmartCaptcha\YandexSmartCaptchaValidator::class],
    ];
}
```

```php
<?= $form->field($model, 'yandexSmartCaptcha')->widget(\matrozov\yii2yandexSmartCaptcha\YandexSmartCaptchaWidget::class) ?>
```

## Additional parameters

### YandexSmartCaptcha

| Name      | Required | Type           | Default value |                                                                      |
|-----------|:--------:|----------------|---------------|----------------------------------------------------------------------|
| clientKey |    +     | string         |               | [Read more](https://yandex.cloud/en/docs/smartcaptcha/concepts/keys) |
| serverKey |    +     | string         |               | [Read more](https://yandex.cloud/en/docs/smartcaptcha/concepts/keys) |

### YandexSmartCaptchaValidator

| Name       | Required | Type           | Default value | Comment                                                                                                                                                                                       |
|------------|:--------:|----------------|---------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| host       |          | string \| bool | false         | Specify host for validate smart captcha response or set true to get host from request<br/>[Read more](https://yandex.cloud/en/docs/smartcaptcha/operations/validate-captcha#service-response) |
| sendUserIp |          | bool           | true          | IP address of the user that originated the request to validate the token.<br/>[Read more](https://yandex.cloud/en/docs/smartcaptcha/operations/validate-captcha)                              |

### YandexSmartCaptchaWidget

| Name           | Required | Type           | Default value                                        | Comment                                                                                                                                                                                                                                                       |
|----------------|:--------:|----------------|------------------------------------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| hl             |          | string \| null | null<br/>_By default language get from app language_ | Widget and challenge language.<br/>Allowed languages: ru, en, be, kk, tt, uk, uz, tr<br/>[Read more](https://yandex.cloud/en/docs/smartcaptcha/concepts/widget-methods#render)                                                                                |
| test           |          | bool           | false                                                | Running CAPTCHA in test mode. The user will always get a challenge. Use this property for debugging and testing only.<br/>[Read more](https://yandex.cloud/en/docs/smartcaptcha/concepts/widget-methods#render)                                               |
| webView        |          | bool           | false                                                | Running CAPTCHA in WebView. You can use it to make user response validation more precise when adding CAPTCHA to mobile apps via WebView.<br/>[Read more](https://yandex.cloud/en/docs/smartcaptcha/concepts/widget-methods#render)                            |
| invisible      |          | bool           | false                                                | Invisible CAPTCHA is a way of connecting the SmartCaptcha widget without the "I’m not a robot" button on the page.<br/>[Read more](https://yandex.cloud/en/docs/smartcaptcha/concepts/invisible-captcha)                                                      |
| shieldPosition |          | string         | top-left                                             | Position of the data processing notice section.<br/>Allowed positions: top-left, center-left, bottom-left, top-right, center-right, bottom-right<br/>[Read more](https://yandex.cloud/en/docs/smartcaptcha/concepts/invisible-captcha#data-processing-notice) |
| hideShield     |          | bool           | false                                                | Hide the data processing notice section.<br/>[Read more](https://yandex.cloud/en/docs/smartcaptcha/concepts/invisible-captcha#data-processing-notice)                                                                                                         |
