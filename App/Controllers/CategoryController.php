<?php

use App\Core\Request;
use App\Models\Category;
use App\Core\Views;
use App\Core\Session;

class CategoryController
{

    public function index(): void
    {
        $categories = (new Category())->getAll();

        Views::render('Category/index', ['categories'=> $categories]);
    }


    public function show(Request $request): void
    {

        $category = new Category();
        $category->setId($request->get('id'));

        $category = $category->getById();

        Views::render('Category/show', ['category'=> $category]);
    }

    public function delete(Request $request): void
    {
        $category = new Category();
        $category->setId($request->get('id'));

        if ($category->delete()){
            Sesssion::set('message', 'Category deleted successfully');
        } else {
            Session::set('message', 'Category not deleted, try again');
        }

        Views::redirect('/category');
    }
}