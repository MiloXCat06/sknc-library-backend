<?php

namespace App\Http\Controllers;

use App\Http\Resources\BorrowResource;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\User;

class PeminjamanController extends Controller
{
    public function index()
    {
        // Tampilkan semua peminjaman
        $borrows = Borrow::all()->withCount('views')->when(request()->search, function ($books) {
            $books = $books->where('name', 'like', '%' . request()->search . '%');
        })->latest()->paginate(5);

        // Append query string to pagination links
        $borrows->appends(['search' => request()->search]);

        // Return with Api Resource
        return new BorrowResource(true, 'List Data Buku', $borrows);
    }

    public function create()
    {
        // Tampilkan form untuk membuat peminjaman baru
        $books = Book::all();
        $members = User::all();
        return view('peminjaman.create', compact('bukus', 'anggotas'));
    }

    public function store(Request $request)
    {
        // Validasi data yang dikirim
        $request->validate([
            'id_book' => 'required',
            'id_member' => 'required',
        ]);

        // Simpan peminjaman baru ke database
        Borrow::create($request->all());

        // Redirect ke halaman utama peminjaman dengan pesan sukses
        return redirect()->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil ditambahkan.');
    }

    public function show(Borrow $borrow)
    {
        // Tampilkan detail peminjaman
        return view('peminjaman.show', compact('peminjaman'));
    }

    public function edit(Borrow $borrow, Request $request)
    {
        // Tampilkan form untuk mengedit peminjaman
        $books = Book::all();
        $members = User::all();
        return view('peminjaman.edit', compact('peminjaman', 'bukus', 'anggotas'));

        // Validasi data yang dikirim
        $request->validate([
            'id_buku' => 'required',
            'id_anggota' => 'required',
        ]);

        // Update peminjaman ke database
        $borrow->update($request->all());

        // Redirect ke halaman utama peminjaman dengan pesan sukses
        return redirect()->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil diperbarui.');
    }

    public function destroy(Borrow $borrow)
    {
        // Hapus peminjaman dari database
        $borrow->delete();

        // Redirect ke halaman utama peminjaman dengan pesan sukses
        return redirect()->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil dihapus.');
    }
}
