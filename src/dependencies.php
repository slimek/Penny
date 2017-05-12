<?php

//======================================================================================================================
// Dependency Injection
//======================================================================================================================

$container = $app->getContainer();

//----------------------------------------------------------------------------------------------------------------------
// Logging
//----------------------------------------------------------------------------------------------------------------------

$container['logger'] = function ($container) {

    $logger = new Monolog\Logger('penny');

    // 1. 路徑是相對於 index.php 所在的 public 目錄
    // 2. 利用 %F 加入日期標記
    $path = strftime('../log/penny_%F.log');

    $handler = new Monolog\Handler\StreamHandler($path);
    $handler->setFormatter(new Monolog\Formatter\LineFormatter(null, null, true, true));
    $logger->pushHandler($handler);

    return $logger;
};
