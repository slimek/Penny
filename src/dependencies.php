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

    // 透過 Fluentd 輸出 log
    $handler = new Dakatsuka\MonologFluentHandler\FluentHandler(
        null,
        $container->settings['fluentdHost']
    );
    $logger->pushHandler($handler);

    return $logger;
};
