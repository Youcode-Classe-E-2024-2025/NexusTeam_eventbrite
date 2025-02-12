<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Views;
use App\Models\Tag;


class TagController{

    public function index(){
        $tags = (new Tag())->getAll();

        Views::render('Tag/index', ['tags'=> $tags]);
    }

    public function show(Request $request): void
    {

        $tag = new Tag($request->get('id'));

        $tag = $tag->getById();
        if ($request->getUri() === "/tags/update/" . $request->get('id')){
            Views::render('Tag/edit', ['tag'=> $tag]);
        }

        Views::render('Tag/show', ['category'=> $tag]);
    }
}