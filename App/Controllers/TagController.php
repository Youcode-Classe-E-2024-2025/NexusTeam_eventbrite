<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Session;
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

    public function store(Request $request): void {
        $tags = $request->get('tags');
        $tags = array_filter(explode(',', trim($tags)));

        $errors = 0;

        foreach ($tags as $tag){
            $tag = new Tag(0, $tag);
            if (!$tag->save()){
                $errors++;
            }
        }

        if ($errors !== 0){
            Session::set('message', "$errors tags not saved, try them again");
        } else {
            Session::set('message', "All tags saved successfully");
        }


        Views::redirect('/tags');


    }
}