<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get user
        $categorie = Category::when(request()->search, function($categories) {
            $categorie = $categories->where('name', 'like', '%'. request()->search . '%');
        })->latest()->paginate(5);

        //append query string to pagination links
        $categorie->appends(['search' => request()->search]);

        //return with Api Resource
        return new CategoryResource(true, 'List Data Categories', $categorie);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /**
         * Validate request
         */
        $validator = Validator::make($request->all(), [
            'image'         => 'required|mimes:jpeg,jpg,png|max:2000',
            'name'          => 'required|unique:categories',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/categories', $image->hashName());

        //create user
        $category = Category::create([
            'image'    => $image->hashName(),
            'name'     => $request->name,
            'slug'     => Str::slug($request->name, '-'),
        ]);

        if($category) {
            //return success with Api Resource
            return new CategoryResource(true, 'Data Category Berhasil Disimpan!', $category);
        }

        //return failed with Api Resource
        return new CategoryResource(false, 'Data Category Gagal Disimpan!', null);
    }

    /**
     * Display the specified resource.
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //get user
        $category = Category::whereId($id)->first();

        if($category) {
            //return success with Api resource
            return new CategoryResource(true, 'Detail Data Category', $category);
        }

        //return failed with Api Resource
        return new CategoryResource(false, 'Detail Data Category Tidak Ditemukan!', null);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category )
    {
        /**
         * validate request
         */
        $validator = Validator::make($request->all(), [
            'name'           => 'required|unique:categories,name,', $category->id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        //check image update
        if ($request->file('image')) {
            
            //remove old image
            Storage::disk('local')->delete('public/categories/'.basename($category->image));

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/categories', $image->hashName());

            //update category with new image
            $category->update([
                'image' => $image->hashName(),
                'name'  => $request->name,
                'slug'  => Str::slug($request->name, '-'),
            ]);

        }

            
        //update category without image
        $category->update([
            'name'  => $request->name,
            'slug'  => Str::slug($request->name, '-'),
        ]);

        if($category) {
            //ruturn success with Api Resource
            return new CategoryResource(true, 'Data Category Berhasil Diupdate!', $category);
        }

        //return failed with Api Resource
        return new CategoryResource(false, 'Data Category Gagal Diupdate!', null);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //remove image
        Storage::disk('local')->delete('public/categories/'.basename($category->image));

        if($category->delete()) {
            //return success with Api Resource
            return new CategoryResource(true, 'Data Category Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new CategoryResource(false, 'Data Category Gagal Dihapus!', null);
    }

    /**
     * all
     * 
     * @return void
     */
    public function all()
    {
        //get categories
        $categorie = Category::latest()->get();

        //return with Api Resource
        return new CategoryResource(true, 'List Data Categories', $categorie);
    }
}