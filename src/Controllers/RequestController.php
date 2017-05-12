<?php
namespace Controllers;

// Composer
use Slim\Http\Request;
use Slim\Http\Response;

// 回應 Request 的各種內容，主要用來協助查看 Request 是否正確。
// GET 和 POST 都可回應
class RequestController
{
    public function viewBody(Request $request, Response $response)
    {
        $params = $request->getParsedBody();

        $headers = $request->getHeaders();
        $headerTexts = [];
        foreach ($headers as $name => $values) {
            $headerTexts[$name] = implode(', ', $values);
        }

        $result = [
            'method'  => $request->getMethod(),
            'headers' => $headerTexts,
            'params'  => $params,
        ];

        return $response->withJson($result)
                        ->withHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
    }
}
