<?php

namespace App\Controllers;
use App\Core\Views;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;


use App\Models\User;
use App\Core\Database;
use Exception;

class ProfileController {
    public function index(){
        Views::render('profile');
    }
}