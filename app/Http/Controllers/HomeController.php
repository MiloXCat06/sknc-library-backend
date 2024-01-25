<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //function yang pertama kali dibuka
    public function index()
    {
        return view('home'); //menampilkan tampilan home
    }

    public function appfeature()
    {
        return view('appfeature');
    }
}
