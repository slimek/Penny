<?php
namespace Controllers;

use Praline\Slim\Controller;
use Slim\Http\Request;
use Slim\Http\Response;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class TwigController extends Controller
{
    public function __construct($container)
    {
    }

    public function hello(Request $request, Response $response)
    {
        $loader = new ArrayLoader([
            'index' => 'Hello {{ name }}',
        ]);
        $twig = new Environment($loader);

        echo $twig->render('index', ['name' => 'Fabien']);
        return $response;
    }
}
