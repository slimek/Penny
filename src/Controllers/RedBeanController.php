<?php
namespace Controllers;

use Praline\Slim\Controller;
use Psr\Log\LoggerInterface;
use RedBeanPHP\R;
use Slim\Http\Request;
use Slim\Http\Response;

// 練習使用 RedBeanPHP 程式庫
class RedBeanController extends Controller
{
    /** @var  LoggerInterface */
    private $logger;

    public function __construct($container)
    {
        $this->logger = $container->logger;

        $db = $container->settings['db'];
        $host = $db['host'];
        $name = $db['name'];

        R::setup("mysql:host=$host;dbname=$name", $db['user'], $db['password']);
    }

    public function insertBook(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $this->checkParams($params, [
            'title' => static::PARAM_STRING,
        ]);

        $book = R::dispense('book');
        $book->title = $params['title'];
        $id = R::store($book);

        $book = R::load('book', $id);

        return $this->withJson($response, $book);
    }
}
