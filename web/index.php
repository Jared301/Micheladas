
<?php

// Habilita el modo desarrollo y debug
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV')   or define('YII_ENV', 'dev');

// Carga el autoloader de Composer y Yii
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

// Carga la configuración principal
$config = require __DIR__ . '/../config/web.php';

// Ejecuta la aplicación
(new yii\web\Application($config))->run();
