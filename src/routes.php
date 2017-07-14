<?php

//======================================================================================================================
// Routes
// - 將 HTTP 請求分派給適當的 Controllers
//======================================================================================================================

use Slim\Http\Request;
use Slim\Http\Response;
use Praline\Slim\Middleware\RouteLogger;
use Praline\Utils\LetterCase;

// Middleware
$routeLogger = new RouteLogger($app->getContainer());

$app->add($routeLogger);

//----------------------------------------------------------------------------------------------------------------------
// 獨立 Actions
//----------------------------------------------------------------------------------------------------------------------

$app->get('/phpinfo', function (Request $request, Response $response) {
    
    return $response->write(phpinfo());
});

$app->get('/hello', function (Request $request, Response $response) {

    return $response->write('Hello World!');
});

$app->get('/echo', function (Request $request, Response $response) {

    $params = $request->getQueryParams();
    return $response->withJson($params);
});

$app->get('/session-throttle', function (Request $request, Response $response) {

    // 如果沒有被 SessionThrottler 擋下來，就回應一個 OK
    return $response->withJson('OK');

})->add(new Middleware\SessionThrottler(5, 10, function (Request $request, Response $response) {

    return $response->withStatus(429);
}));

$app->get('/sqlite-throttle', function (Request $request, Response $response) {

    // 如果沒有被 SqliteThrottler 擋下來，就回應一個 OK
    return $response->withJson('OK');

})->add(new Middleware\SqliteThrottler(5, 10,  function (Request $request, Response $response) {

    return $response->withStatus(429);

}))->add(new RKA\Middleware\IpAddress(true, []));


//----------------------------------------------------------------------------------------------------------------------
// IP Controller
// - 取得 request 來源 IP 的各種資訊
//----------------------------------------------------------------------------------------------------------------------

$app->get('/ip/{action}', function (Request $request, Response $response, $args) {

    $controller = new Controllers\IpController($this);
    $methodName = LetterCase::kebabToCamel($args['action']);
    return $controller->{$methodName}($request, $response);

})->add(new RKA\Middleware\IpAddress(true, []));

//----------------------------------------------------------------------------------------------------------------------
// Request Controller
// - 用來檢查 requests 的內容是否正確
//----------------------------------------------------------------------------------------------------------------------

$app->any('/request/{action}', function (Request $request, Response $response, $args) {

    $controller = new Controllers\RequestController($this);
    $methodName = LetterCase::kebabToCamel($args['action']);
    return $controller->{$methodName}($request, $response);
});

//----------------------------------------------------------------------------------------------------------------------
// RedBean Controller
// - 練習 RedBean 程式庫
//----------------------------------------------------------------------------------------------------------------------

$app->post('/red-bean/{action}', function (Request $request, Response $response, $args) {

    $controller = new Controllers\RedBeanController($this);
    $methodName = LetterCase::kebabToCamel($args['action']);
    return $controller->{$methodName}($request, $response);
});
