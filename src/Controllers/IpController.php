<?php
namespace Controllers;

// Composer
use GuzzleHttp\Client;
use Monolog\Logger;
use Slim\Http\Request;
use Slim\Http\Response;

class IpController
{
    /** @var  Logger */
    private $logger;

    public function __construct($container)
    {
        $this->logger = $container->logger;
    }

    public function traceIp(Request $request, Response $response)
    {
        $ipAddress = $request->getAttribute('ip_address');
        $remoteAddr = $_SERVER['REMOTE_ADDR'];

        $this->logger->info("ipAddress: '$ipAddress', remoteAddr: '$remoteAddr'");

        $result = [
            'ipAddress' => $ipAddress,
            'remoteAddr' => $remoteAddr,
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
