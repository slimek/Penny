<?php
namespace Controllers;

use Praline\Slim\Controller;
use Slim\Http\Request;
use Slim\Http\Response;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\Loader\FilesystemLoader;

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

    public function firstPage(Request $request, Response $response)
    {
        $loader = new FilesystemLoader('../src/Views');
        $twig = new Environment($loader);

        $navigation = [
            [
                'href' => 'https://google.com',
                'caption' => 'Google',
            ],
            [
                'href' => 'https://microsoft.com',
                'caption' => 'Microsoft',
            ],
        ];

        echo $twig->render('first-page.html.twig', [
            'navigation' => $navigation,
        ]);
        return $response;
    }
}
