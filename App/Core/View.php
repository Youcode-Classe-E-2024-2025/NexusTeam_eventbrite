<?php

namespace App\Core;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class View
{
    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public static function render($view, $data = []): void
    {
        $loader  = new \Twig\Loader\FilesystemLoader(__DIR__ . "/../../Views");
        $twig = new \Twig\Environment($loader);
        $view = "$view.twig";

        echo $twig->render($view, $data);
    }
}