<?php
namespace Controllers;

use Praline\Slim\Controller;
use PropelModels\{Author, AuthorQuery, Book};
use Slim\Http\Request;
use Slim\Http\Response;

// 練習使用 Propel 程式庫
class PropelController extends Controller
{
    public function __construct($container)
    {

    }

    public function insertAuthor(Request $request, Response $response)
    {
        $params = $request->getParsedBody();

        $author = new Author();
        $author->setName($params['name']);
        $author->save();

        $result = [
            'id' => $author->getId(),
        ];

        return $this->withJson($response, $result);
    }

    public function selectAuthors(Request $request, Response $response)
    {
        $authors = AuthorQuery::create()->find();

        $result = [
            'authors' => $authors->toArray(),
        ];

        return $this->withJson($response, $result);
    }
}
