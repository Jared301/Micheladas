<?php

$params = require __DIR__ . '/params.php';
$db     = require __DIR__ . '/db.php';

$config = [
    'id'         => 'basic',
    'basePath'   => dirname(__DIR__),
    'bootstrap'  => ['log'],
    'aliases'    => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'R33kuTvVO48NdoM5sv2VlMRskp7SEqMB',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass'   => 'app\models\Empleado',
            'enableAutoLogin' => true,
            'loginUrl'        => ['site/login'],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'formatter' => [
            'class'             => 'yii\i18n\Formatter',
            'currencyCode'      => 'MXN',
            'dateFormat'        => 'php:d/m/Y',
            'datetimeFormat'    => 'php:d/m/Y H:i:s',
            'timeFormat'        => 'php:H:i:s',
            'decimalSeparator'  => '.',
            'thousandSeparator' => ',',
            'nullDisplay'       => '<span class="not-set">N/A</span>',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class'            => \yii\symfonymailer\Mailer::class,
            'viewPath'         => '@app/mail',
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'enableStrictParsing' => false,
            'rules' => [
                ''                       => 'site/index',
                'login'                  => 'site/login',
                'logout'                 => 'site/logout',
                'dashboard'              => 'site/dashboard',
                'clientes'               => 'clientes/index',
                'clientes/<id:\d+>'     => 'clientes/view',
                'clientes/crear'         => 'clientes/create',
                'clientes/actualizar/<id:\d+>' => 'clientes/update',
                'clientes/eliminar/<id:\d+>'   => 'clientes/delete',
                
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;