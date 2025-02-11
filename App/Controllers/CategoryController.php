<?php
namespace App\Controllers;

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

        if ($request->getUri() === "/category/update/" . $request->get('id')){
            Views::render('Category/edit', ['category'=> $category]);
        }

        Views::render('Category/show', ['category'=> $category]);
    }

    public function destroy(Request $request): void
    {
        $category = new Category();
        $category->setId($request->get('id'));

        if ($category->delete()){
            Session::set('message', 'Category deleted successfully');
        } else {
            Session::set('message', 'Category not deleted, try again');
        }

        Views::redirect('/category');
    }
}