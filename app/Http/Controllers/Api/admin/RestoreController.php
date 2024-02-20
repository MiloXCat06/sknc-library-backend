<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class RestoreController extends Controller
{
    public function returnBook($id)
    {
        $book = Book::findOrFail($id);
        $book->status = false;
        $book->save();
        return response()->json(['message' => 'Book returned successfully']);
    }
}
