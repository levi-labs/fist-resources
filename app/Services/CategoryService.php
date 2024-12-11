<?php


namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function searchCategory($name)
    {
        return Category::where('name', 'like', '%' . $name . '%')->get();
    }
    public function getAllCategories()
    {
        return Category::all();
    }

    public function getCategoryById($id)
    {
        return Category::find($id);
    }
    public function createCategory($data)
    {
        try {
            return Category::create($data);
        } catch (\Throwable $error) {
            throw $error;
        }
    }

    public function updateCategory($id, $data)
    {
        try {
            $category = Category::find($id);
            $category->update($data);
            return $category;
        } catch (\Throwable $error) {
            throw $error;
        }
    }

    public function deleteCategory($id)
    {
        try {
            $category = Category::find($id);
            $category->delete();
        } catch (\Throwable $error) {
            throw $error;
        }
    }
}
