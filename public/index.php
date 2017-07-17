<?php

//----------------------------------------------------------------------------------------------------------------------
// PHP Settings
//----------------------------------------------------------------------------------------------------------------------

date_default_timezone_set('Asia/Taipei');

// 啟動 Session，才能使用 Middleware\SessionThrottle
session_start();

// 設定 RedBeanPHP
define('REDBEAN_MODEL_PREFIX', 'RedBeanModels\\');

//----------------------------------------------------------------------------------------------------------------------
// PHP Autoloading
//----------------------------------------------------------------------------------------------------------------------

// 由 Composer 管理的第三方套件
require __DIR__ . '/../vendor/autoload.php';

// 其他目錄的類別加在這邊

// 將 src 目錄下的類別加入 autoload 機制中
spl_autoload_register(function ($className) {
    $filePath = __DIR__ . '/../src/' . str_replace('\\', '/', $className) . '.php';
    if (file_exists($filePath)) {
        require $filePath;
    } else {
        return false;  // 不能擲出異常，否則 class_exists() 會出問題
    }
});

//----------------------------------------------------------------------------------------------------------------------
// Slim Framework App
//----------------------------------------------------------------------------------------------------------------------

$settings = require __DIR__ . '/../config/settings.php';

$app = new \Slim\App(['settings' => $settings]);

require __DIR__ . '/../src/dependencies.php';
require __DIR__ . '/../src/routes.php';

$app->run();
