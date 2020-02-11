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
        $surat = \App\Surat::find($id);

        $time = \Carbon\Carbon::createFromTimeString(is_null($surat->tanggal_terbit) ? $surat->tanggal_terbit : $surat->updated_at);
        $nomor_surat = sprintf("B-%04u/Un.05/III.4/TL.10/%02u/%u", $surat->nomor_surat, $time->month, $time->year);
        $surat->nomor_surat = $nomor_surat;

        return view("admin.sunting.$surat->jenis_surat", [
            'surat' => $surat,
            'program_studi' => \App\ProgramStudi::all(),
            // 'pemohon' => $surat->pemohon
            ]);
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
