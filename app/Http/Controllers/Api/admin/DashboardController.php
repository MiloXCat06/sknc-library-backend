<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookView;
use App\Models\Borrow;
use App\Models\Restore;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index ()
    {
        $this->middleware('auth'); // Middleware untuk memastikan pengguna sudah terautentikasi
        $this->middleware('role:admin'); // Middleware untuk memastikan pengguna memiliki peran admin
        $this->middleware('can:view_data'); // Middleware untuk memastikan pengguna memiliki izin untuk melihat data
    }

    public function getAllData()
    {
        $books = $this->getBooks();
        $borrows = $this->getBorrows();
        $restores = $this->getRestores();
        $users = $this->getUsers();

        return response()->json([
            'books' => $books,
            'borrows' => $borrows,
            'restores' => $restores,
            'users' => $users
        ]);
    }

    protected function getBooks()
    {
        $books = Book::all();

        return response()->json([
            'books' => $books,
        ]);
    }

    protected function getBorrows()
    {
        $borrows = Borrow::all();

        return response()->json([
            'borrows' => $borrows,
        ]);
    }

    protected function getRestores()
    {
        $restores = Restore::all();
        
        return response()->json([
            'restores' => $restores,
        ]);
    }

    protected function getUsers()
    {
        $users = User::all();

        return response()->json([
            'users' => $users,
        ]);
    }
}
