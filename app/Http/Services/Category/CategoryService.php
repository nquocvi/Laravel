<?php

namespace App\Http\Services\Category;

use App\Models\Category;
use Illuminate\Support\Facades\Session;

class CategoryService 
{

    public function getParent()
    {
        return Category::where('parent_id', 0)->get();
    }

    public function getCategories()
    {
        return Category::all();
    }

    public function create($request)
    {
       try {
            // $data = $request->input();
            Category::create([
                'name' => (string) $request->input('name'),
                'parent_id' => (string) $request->input('parent_id'),
                'description' => (string) $request->input('description'),
                'content' => (string) $request->input('description-detail'),
                'active' => (string) $request->input('active'),
            ]);

            Session::flash('success','Successful');
       } catch (\Exception $err) {
            Session::flash('error',$err->getMessage());
            return false;
       }

       return true;
    }
}