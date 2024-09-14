<?php

namespace App\Repositories\Category;

use App\Models\Category\Category;

class CategoryRepository implements CategoryRepositoryInterface{

    public function index()
    {
        $req = [
            'sort' => request()->has('sort') ? request('sort') : 'updated_at',
            'order' => request()->has('order') ? request('order') : 'desc',
            'limit' => request()->has('limit') ? request('limit') : '25',
            'search' => request()->has('search') ? request('search') : null ,
        ];

        try {
            $category = Category::where(function($query) use ($req){
                if($req['search'])
                $query->where('title' , 'like' , '%'.$req['search'].'%');
            })
            ->orderBy($req['sort'], $req['order'])
            ->paginate($req['limit']);

            return $category;
        
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function store($request)
    {
        try {
            Category::create([
                'title' => $request->title,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update($category, $request)
    {
        try {
            $category->update([
                'title' => $request->title ? $request->title : $category->title,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function delete($category)
    {
        try {
            $category = Category::where('id' , $category)->first();
            $category->delete();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}