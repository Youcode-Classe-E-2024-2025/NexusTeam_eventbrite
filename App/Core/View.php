<?php

namespace App\Core;

use JetBrains\PhpStorm\NoReturn;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class View
{
    private static $path = __DIR__ . "/../Views";

    public static function render($view, $data = []): void
    {
        $loader  = new \Twig\Loader\FilesystemLoader(self::$path);
        $twig = new \Twig\Environment($loader, [
            'debug' => true,
        ]);
        $twig->addExtension(new \Twig\Extension\DebugExtension());

        $view = "$view.twig";

        if (file_exists(self::$path . "/$view")) {
            echo $twig->render($view, $data);
        } else {
            echo $twig->render('404.twig', []);
        }
    }

    #[NoReturn] public static function redirect($url): void
    {
        header("Location: $url");
        exit;
    }
}