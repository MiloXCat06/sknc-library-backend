<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrow;
use App\Http\Resources\BorrowResource;
use App\Http\Controllers\Controller;

class BorrowController extends Controller
{
    public function index()
    {
        $borrows = Borrow::latest()->paginate(5);
        // Return with Api Resource
        return new BorrowResource(true, 'List Data Buku', $borrows);
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required',
            'user_id' => 'required',
        ]);

        $borrow = Borrow::create($request->all());

        if ($borrow) {
            //return success with Api Resource
            return new BorrowResource(true, 'Data book Berhasil Disimpan!', $borrow);
        }

        //return failed with Api Resource
        return new BorrowResource(false, 'Data book Gagal Disimpan!', null);
    }

    public function show(Borrow $borrow, $id)
    {
        //get borrow
        $borrow = Borrow::whereId($id)->first();

        if ($borrow) {
            //return success with Api resource
            return new BorrowResource(true, 'Detail Data book', $borrow);
        }

        //return failed with Api Resource
        return new BorrowResource(false, 'Detail Data book Tidak Ditemukan!', null);
    }

    public function update(Request $request, Borrow $borrow)
    {
        $request->validate([
            'book_id' => 'required',
            'user_id' => 'required',
        ]);

        $borrow->update($request->all());

        if ($borrow) {
            //ruturn success with Api Resource
            return new BorrowResource(true, 'Data book Berhasil Diupdate!', $borrow);
        }

        //return failed with Api Resource
        return new BorrowResource(false, 'Data book Gagal Diupdate!', null);
    }

    public function destroy(Borrow $borrow)
    {
        //delete 
        if ($borrow->delete()) {
            //return success with Api Resource
            return new BorrowResource(true, 'Data book Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new BorrowResource(false, 'Data book Gagal Dihapus!', null);
    }
}