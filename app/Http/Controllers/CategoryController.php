<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $category;
    public function __construct(CategoryService $category)
    {
        $this->category = $category;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input('search', '');
        $sanitazedSearch = strip_tags($search);
        if (isset($sanitazedSearch) && $sanitazedSearch != '') {

            $title = 'Category List';
            $categories = $this->category->searchCategory($sanitazedSearch);
            return view('pages.category.index', compact('categories', 'title'));
        } else {
            $title = 'Category List';
            $categories = $this->category->getAllCategories();

            return view('pages.category.index', compact('categories', 'title'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Add New Category';

        return view('pages.category.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        try {
            $this->category->createCategory($request->validated());

            return redirect()->route('category.index')->with('success', 'Category created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $title = 'Edit Category';

        $category = $this->category->getCategoryById($category->id);

        return view('pages.category.edit', compact('title', 'category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Category $category, CategoryRequest $request)
    {
        try {
            $this->category->updateCategory($category->id, $request->validated());
            return redirect()->route('category.index')->with('success', 'Category updated successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $this->category->deleteCategory($category->id);

            return redirect()->route('category.index')->with('success', 'Category deleted successfully');
        } catch (\Throwable $error) {
            return redirect()->back()->with('error', $error->getMessage());
        }
    }
}
