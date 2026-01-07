<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\ResponseHelper;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories =  Category::all();
       return ResponseHelper::success(' جميع الأصناف',$categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = new Category();
        $category->name = $request->name;
        $category->save();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $category->id . '.' . $file->extension();
            Storage::putFileAs('category-images', $file, $filename);
            $category->image = $filename;
            $category->save();
        }

        return ResponseHelper::success("تمت إضافة الصنف", $category);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $id)
    {
        $category = Category::find($id);
        
        if (!$category) {
            return ResponseHelper::failed("الصنف غير موجود");
        }

        $category->name = $request->name;

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::delete('category-images/' . $category->image);
            }
            
            $file = $request->file('image');
            $filename = $category->id . '.' . $file->extension();
            Storage::putFileAs('category-images', $file, $filename);
            $category->image = $filename;
        }

        $category->save();
        return ResponseHelper::success("تم تعديل الصنف", $category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        
        if (!$category) {
            return ResponseHelper::failed("الصنف غير موجود");
        }

        if ($category->image) {
            Storage::delete('category-images/' . $category->image);
        }

        $category->delete();
        return ResponseHelper::success("تم حذف الصنف", $category);
    }
}
