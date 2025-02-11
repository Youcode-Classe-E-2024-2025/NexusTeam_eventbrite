<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Validator;
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

    public function update(Request $request): void
    {
        Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:40',
        ]);

        if (!empty(Validator::errors())){
            Session::set('message', Validator::errors()[0]);
            Views::redirect('/category/update/' . $request->get('id'));
            exit;
        }

        $category = new Category();
        $category->fill($request->all());

        if ($category->update()){
            Session::set('message', 'Category updated successfully');
        } else {
            Session::set('message', 'Category not updated, try again');
        }

        Views::redirect('/category');
    }
}