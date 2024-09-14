<?php

namespace App\Http\Controllers\V1\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category\Category;
use App\Repositories\Category\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    private $categoryRepo ;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepo = $categoryRepository;
    }


    public function index()
    {
        return $this->categoryRepo->index();
    }

    public function store(CreateCategoryRequest $request)
    {
        $user = Auth::user();

        if(! $user){
            return 'عدم دسترسی';
        }

        $error = $this->categoryRepo->store($request);

        if ($error === null) {
            return response()->json(['message' => __('messages.category.store.success', ['title' => $request->title])], 201);
        }
        return response()->json(['message' => __('messages.category.store.failed', ['title' => $request->title])], 500);
    }

    public function show(Category $category)
    {
        $user = Auth::user();
        
        if(! $user){
            return 'عدم دسترسی';
        }
        //
    }

    public function update(Category $category, UpdateCategoryRequest $request)
    {
        $user = Auth::user();
        
        if(! $user){
            return 'عدم دسترسی';
        }

        $error = $this->categoryRepo->update($category, $request);
        if ($error === null) {
            return response()->json(['message' => __('messages.category.update.success', ['title' => $category->title])], 200);
        }
        return response()->json(['message' => __('messages.category.update.failed', ['title' => $category->title])], 500);
    }

    public function destroy($category)
    {    
        $user = Auth::user();
        
        if(! $user){
            return 'عدم دسترسی';
        }

        $error = $this->categoryRepo->delete($category);
        if ($error === null) {
            return response()->json(['message' => __('messages.category.delete.success')], 200);
        }
        return response()->json(['message' => __('messages.category.delete.failed')], 500);
    }}
