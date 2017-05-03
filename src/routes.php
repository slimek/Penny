<?php

use Slim\Http\Request;
use Slim\Http\Response;

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
