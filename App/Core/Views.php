<?php

namespace App\Core;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Views
{
    private static string $path = __DIR__ . "/../Views";

    public static function render($view, $data = []): void
    {
        $loader  = new \Twig\Loader\FilesystemLoader(self::$path);
        $twig = new \Twig\Environment($loader, [
            'debug' => true,
        ]);
        $twig->addExtension(new \Twig\Extension\DebugExtension());

        $view = "$view.twig";

        Session::start();

        if (isset($_SESSION['message'])){
            $data['message'] = $_SESSION['message'];
            unset($_SESSION['message']);
        }

        if (isset($_SESSION['user'])){
            $data['user'] = $_SESSION['user'];
        }

        if (file_exists(self::$path . "/$view")) {
            echo $twig->render($view, $data);
        } else {
            echo $twig->render('404.twig', []);
        }
    }

    public static function redirect($url): void
    {
        header("Location: $url");
    }
}