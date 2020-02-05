<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.home');
    }

    public function semua()
    {
        return view('admin.riwayat');
    }

    public function sunting($id)
    {
        # code...
    }

    public function pengaturanUmum()
    {
        # code...
    }

    public function laporan()
    {
        # code...
    }
}
