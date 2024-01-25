<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get books
        $books = Book::with('user')->withCount('views')->when(request()->search, function($books) {
            $books = $books->where('name', 'like', '%'. request()->search . '%');
        })->where('user_id', auth()->user()->id)->latest()->paginate(5);

        //append query string to pagination links
        $books->appends(['search' => request()->search]);

        //return with Api Resource
        return new BookResource(true, 'List Data Posts', $books);
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
            'title' => 'required|unique:books',
            'synopsis' => 'required',
            'isbn' => 'nullable|string',
            'writer' => 'nullable|string',
            'page_amount' => 'nullable|integer',
            'stock_amount' => 'nullable|integer',
            'published' => 'nullable|date',
            'category' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'status' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/books', $image->hashName());

        //create book
        $book = book::create([
            'title' => $request->input('title'),
            'synopsis' => $request->input('synopsis'),
            'isbn' => $request->input('isbn'),
            'writer' => $request->input('writer'),
            'page_amount' => $request->input('page_amount'),
            'stock_amount' => $request->input('stock_amount'),
            'published' => $request->input('published'),
            'category' => $request->input('category'),
            'image' => $image->hashName(),
            'status' => $request->input('status'),
        ]);

        //push notifications firebase
        fcm()
            ->toTopic('push-notifications')
            ->priority('normal')
            ->timeToLive(0)
            ->notification([
                'titel'         => 'Berita Baru !',
                'body'          => 'Disini akan menampilkan judul berita baru',
                'click_action'  => 'OPEN_ACTIVITY'
            ])
            ->send();

        if($book) {
            //return success with Api Resource
            return new BookResource(true, 'Data book Berhasil Disimpan!', $book);
        }

        //return failed with Api Resource
        return new BookResource(false, 'Data book Gagal Disimpan!', null);
    }

    /**
     * Display the specified resource.
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //get book$book
        $book = book::with('category')->whereId($id)->first();

        if($book) {
            //return success with Api resource
            return new BookResource(true, 'Detail Data book', $book);
        }

        //return failed with Api Resource
        return new BookResource(false, 'Detail Data book Tidak Ditemukan!', null);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, book $book)
    {
        /**
         * validate request
         */
        $validator = Validator::make($request->all(), [
            'titel'          => 'required|unique:books,titel,'.$book->id,
            'category_id'    => 'required',
            'content'        => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

            //check image update
            if ($request->file('image')) {
            
                //remove old image
                Storage::disk('local')->delete('public/books/'.basename($book->image));

                //upload new image
                $image = $request->file('image');
                $image->storeAs('public/books', $image->hashName());

                $book->update([
                    'title' => $request->input('title'),
                    'synopsis' => $request->input('synopsis'),
                    'isbn' => $request->input('isbn'),
                    'writer' => $request->input('writer'),
                    'page_amount' => $request->input('page_amount'),
                    'stock_amount' => $request->input('stock_amount'),
                    'published' => $request->input('published'),
                    'category' => $request->input('category'),
                    'image' => $image->hashName(),
                    'status' => $request->input('status'),
                ]);

                $book->update([
                    'title' => $request->input('title'),
                    'synopsis' => $request->input('synopsis'),
                    'isbn' => $request->input('isbn'),
                    'writer' => $request->input('writer'),
                    'page_amount' => $request->input('page_amount'),
                    'stock_amount' => $request->input('stock_amount'),
                    'published' => $request->input('published'),
                    'category' => $request->input('category'),
                    'status' => $request->input('status'),
                ]);
            }

        if($book) {
            //ruturn success with Api Resource
            return new BookResource(true, 'Data book Berhasil Diupdate!', $book);
        }

        //return failed with Api Resource
        return new BookResource(false, 'Data book Gagal Diupdate!', null);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(book $book)
    {
        //remove image
        Storage::disk('local')->delete('public/books/'.basename($book->image));

        //delete 
        if($book->delete()) {
            //return success with Api Resource
            return new BookResource(true, 'Data book Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new BookResource(false, 'Data book Gagal Dihapus!', null);
    }
}
