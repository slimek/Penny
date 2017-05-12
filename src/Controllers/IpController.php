<?php
namespace Controllers;

// Composer
use GuzzleHttp\Client;
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

    // 查詢 request 的來源 IP 資訊
    public function ipInfo(Request $request, Response $response)
    {
        $ipAddress = $request->getAttribute('ip_address');

        $client = new Client(['base_uri' => 'https://ipinfo.io/']);
        $res = $client->request('GET', $ipAddress . '/json');

        if ($res->getStatusCode() != 200) {
            throw new \Exception(
                'Call to ipinfo.io failed, status code: ' . $res->getStatusCode()
            );
        }

        $ipInfo = json_decode($res->getBody());
       return $response->withJson($ipInfo);
    }
}
