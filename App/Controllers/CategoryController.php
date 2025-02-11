<?php

use App\Models\Category;
use App\Core\Views;

class CategoryController
{

    public function index(){
        $categories = (new Category())->getAll();

        Views::render('Category/index', ['categories'=> $categories]);
    }


    public function show(Request $request){

        $category = new Category();
        $category->setId($request->get('id'));

        $category = $category->getById();

        Views::render('Category/show', ['category'=> $category]);
    }
}