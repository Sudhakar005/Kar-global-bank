<?php
use \yii\web\Request;
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/test_db.php';

/**
 * Application configuration shared by all test types
 */
return [
    'id' => 'basic-tests',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'language' => 'en-US',
    'components' => [
        'db' => $db,
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
            'messageClass' => 'yii\symfonymailer\Message'
        ],
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'errorHandler' => [
            'errorAction' => 'bank-api/not-found',
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/info' => '/bank-api/get-bank-info',
                '/account' => '/bank-api/get-account-list',
                '/account/<id:\d+>' => '/bank-api/get-account-info',
                '/account/checking/<id:\d+>' => '/bank-api/get-account-type-info',
                '/account/create' => '/bank-api/create-account',
                '/account/modify' => '/bank-api/update-account',
                '/account/remove' => '/bank-api/delete-account',
                '/account/deposit' => '/bank-api/make-transaction',
                '/account/withdrawal' => '/bank-api/make-transaction',
                '/account/transfer' => '/bank-api/make-transaction',
                '/' => '/bank-api/index'
            ],
        ],
        'user' => [
            'identityClass' => 'app\models\User',
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
            'baseUrl' => str_replace('/web', '', (new Request)->getBaseUrl()),
            // but if you absolutely need it set cookie domain to localhost
            /*
            'csrfCookie' => [
                'domain' => 'localhost',
            ],
            */
        ],
    ],
    'params' => $params,
];
