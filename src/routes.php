<?php

//======================================================================================================================
// Routes
// - 將 HTTP 請求分派給適當的 Controllers
//======================================================================================================================

use Slim\Http\Request;
use Slim\Http\Response;
use Praline\LetterCase;

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
