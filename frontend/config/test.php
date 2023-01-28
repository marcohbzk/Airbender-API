<?php
$testConfig = array_merge(
    require(__DIR__ . '/main.php'),
    require(__DIR__ . '/main-local.php'),
    [
        'id' => 'app-frontend-tests',
        'components' => [
            'assetManager' => [
                'basePath' => __DIR__ . '/../web/assets',
            ],
            'urlManager' => [
                'showScriptName' => true,
            ],
            'request' => [
                'cookieValidationKey' => 'test',
            ],
            'mailer' => [
                'messageClass' => \yii\symfonymailer\Message::class
            ],
            'db' => [
                'dsn' => 'mysql:host=localhost;dbname=airbender_test',
                'username' => 'marco',
                'password' => '5610',
                'charset' => 'utf8',
            ]
        ],

    ]
);

return $testConfig;
