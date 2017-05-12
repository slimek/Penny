<?php
namespace Controllers;

// Composer
use Slim\Http\Request;
use Slim\Http\Response;

class IpController
{
    public function traceIp(Request $request, Response $response)
    {
        $result = [
            'ipAddress' => $request->getAttribute('ip_address'),
            'remoteAddr' => $_SERVER['REMOTE_ADDR'],
        ];

        return $response->withJson($result);
    }
}
