<?php

//======================================================================================================================
// Dependency Injection
//======================================================================================================================

$container = $app->getContainer();

//----------------------------------------------------------------------------------------------------------------------
// Logging
//----------------------------------------------------------------------------------------------------------------------

$container['logger'] = function ($container) {

    $settings = $container->settings;

    $logger = new Monolog\Logger('penny');

    // 輸出 log 到檔案
    if ($settings['enableFileLog']) {
        $path = strftime('/var/log/penny/penny-%F.log');
        $handler = new Monolog\Handler\StreamHandler($path);
        $handler->setFormatter(new Monolog\Formatter\LineFormatter(null, null, true, true));
        $logger->pushHandler($handler);
    }

    if ($settings['enableFluetndLog']) {
        // 透過 Fluentd 輸出 log
        $handler = new Dakatsuka\MonologFluentHandler\FluentHandler(
            null,
            $container->settings['fluentdHost']
        );
        $logger->pushHandler($handler);
    }

    return $logger;
};
