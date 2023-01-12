<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CreateFormRequest;
use App\Http\Services\Category\CategoryService;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService =  $categoryService;
    }

    public function index()
    {
        return view('admin.category.add_category',[
            'title' => 'Add category',
            'category' => $this->categoryService->getParent()
        ]);
    }

    public function store(CreateFormRequest $request)
    {
        $this->categoryService->create($request);
        return redirect()->back();
    }

    public function viewCategories()
    {
        return view('admin.category.view_category',[
            'title' => 'List category',
            'categories' => $this->categoryService->getCategories()
        ]);
    }

    
}
