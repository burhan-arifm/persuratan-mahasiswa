<?php

namespace App\Http\Controllers;

use App\Surat;
use App\Mahasiswa;
use Illuminate\Http\Request;

class SuratController extends Controller
{
    public function formPengajuan($kode_surat)
    {
        try {
            return view("surat.form.$kode_surat", ['program_studi' => \App\ProgramStudi::all()]);
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    public function ajukan(Request $request)
    {
        $pemohon = "";

        if ($request->tipe_surat == "izin-kunjungan") {
            $detail = \App\IzinKunjungan::create([
                'instansi_penerima' => $request->instansi_penerima,
                'alamat_instansi' => $request->alamat_instansi,
                'mata_kuliah' => $request->mata_kuliah,
                'program_studi' => $request->program_studi,
                'semester' => $request->semester,
                'kelas' => $request->kelas,
                'dosen_pengampu' => $request->dosen_pengampu,
                'tanggal_kunjungan' => \Carbon\Carbon::parseFromLocale($request->tanggal_kunjungan, config('app.locale'))->format("Y-m-d"),
                'waktu_kunjungan' => $request->waktu_kunjungan
            ]);
            $pemohon = "$request->program_studi $request->semester-$request->kelas";
        } else {
            $mahasiswa = Mahasiswa::updateOrCreate(
                ['nim' => $request->nim],
                ['nama' => $request->nama_mahasiswa,
                 'program_studi' => $request->program_studi,
                 'tanggal_lahir' => \Carbon\Carbon::parseFromLocale($request->tanggal_lahir, config('app.locale'))->format("Y-m-d"),
                 'alamat' => $request->alamat,
                 'no_telepon' => $request->no_telepon]
            );
            $pemohon = $mahasiswa->nim;
    
            switch ($request->tipe_surat) {
                case 'izin-observasi':
                    $mahasiswa->pembimbing_studi = $request->pembimbing_studi;
                    $mahasiswa->save();
                    $detail = \App\IzinObservasi::create([
                        'lokasi_observasi' => $request->lokasi_observasi,
                        'alamat_lokasi' => $request->alamat_lokasi,
                        'topik_skripsi' => $request->topik_skripsi
                    ]);
                    break;
                case 'izin-praktik':
                    $detail = \App\IzinPraktik::create([
                        'instansi_penerima' => $request->instansi_penerima,
                        'alamat_instansi' => $request->alamat_instansi,
                        'mata_kuliah' => $request->mata_kuliah,
                        'dosen_pengampu' => $request->dosen_pengampu    
                    ]);
                    break;
                case 'izin-riset':
                    $detail = \App\IzinRiset::create([
                        'lokasi_riset' => $request->lokasi_riset,
                        'alamat_lokasi' => $request->alamat_lokasi,
                        'judul_skripsi' => $request->judul_skripsi,
                        'pembimbing_1' => $request->pembimbing_1,
                        'pembimbing_2' => $request->pembimbing_2
                    ]);
                    break;
                case 'job-training':
                    $detail = \App\JobTraining::updateOrCreate(
                        ['instansi_penerima' => $request->instansi_penerima,
                         'alamat_instansi' => $request->alamat_instansi],
                        ['dosen_pembimbing' => $request->dosen_pembimbing]
                    );
                case 'permohonan-munaqasah':
                    $detail = \App\PermohonanMunaqasah::create([
                        'judul_skripsi' => $request->judul_skripsi,
                        'pembimbing_1' => $request->pembimbing_1,
                        'pembimbing_2' => $request->pembimbing_2
                    ]);
                    break;
                case 'pernyataan-masih-kuliah':
                    $pangol = explode(" - ", $request->pangkat_golongan);
                    $detail = \App\MasihKuliah::create([
                        'nama_orang_tua' => $request->nama_orang_tua,
                        'nip_orang_tua' => $request->nip_orang_tua,
                        'pangkat' => $pangol[0],
                        'golongan' => $pangol[1],
                        'instansi' => $request->instansi
                    ]);
                    break;
                case 'ppm':
                    $detail = \App\PPM::updateOrCreate(
                        ['instansi_penerima' => $request->instansi_penerima,
                         'alamat_instansi' => $request->alamat_instansi],
                        ['dosen_pembimbing' => $request->dosen_pembimbing]
    
                    );
                    break;
                case 'surat-keterangan':
                    $detail = \App\Keterangan::create([
                        'keperluan' => $request->keperluan
                    ]);
                    break;
                case 'permohonan-komprehensif':
                
                default:
                    break;
            }
        }
        

        $surat_terakhir = (Surat::whereYear('created_at', \Carbon\Carbon::now()->year)->count() != 0)
            ? Surat::whereYear('created_at', \Carbon\Carbon::now()->year)->latest()->first()->nomor_surat
            : 0;
        $surat = Surat::create([
            'nomor_surat' => $surat_terakhir + 1,
            'jenis_surat' => $request->tipe_surat,
            'pemohon' => $pemohon,
            'surat' => $detail->id,
            'status_surat' => "Belum Diproses",
            'tanggal_terbit' => \Carbon\Carbon::now()->format('LL')
        ]);
        event(new \App\Events\SuratDiajukan($surat));

        return view('welcome');
    }

    public function semua()
    {
        $persuratan = Surat::all();
        $letters = [];

        foreach ($persuratan as $surat) {
            $time = \Carbon\Carbon::createFromTimeString(is_null($surat->tanggal_terbit) ? $surat->tanggal_terbit : $surat->updated_at);
            $nomor_surat = sprintf("B-%04u/Un.05/III.4/TL.10/%02u/%u", $surat->nomor_surat, $time->month, $time->year);
            if ($surat->jenis_surat == 'izin-kunjungan') {
                $surat_simplified = array(
                    'id' => $surat->id,
                    'nomor_surat' => $nomor_surat,
                    'jenis_surat' => $surat->jenis->jenis_surat,
                    'identitas' => $surat->izin_kunjungan->program_studi,
                    'pemohon' => $surat->izin_kunjungan->semester.'-'.$surat->izin_kunjungan->kelas,
                    'waktu_readable' => $time->isoFormat('LLLL'),
                    'waktu' => $surat->updated_at
                );
            } else {
                $surat_simplified = array(
                    'id' => $surat->id,
                    'nomor_surat' => $nomor_surat,
                    'jenis_surat' => $surat->jenis->jenis_surat,
                    'identitas' => $surat->mahasiswa->nim,
                    'pemohon' => $surat->mahasiswa->nama,
                    'waktu_readable' => $time->isoFormat('LLLL'),
                    'waktu' => $surat->updated_at
                );
            }
            
            $letters[] = $surat_simplified;
        }

        return $letters;
    }

    public function terbaru()
    {
        $persuratan =  Surat::where('status_surat', "Belum Diproses")->get();
        $letters = [];

        foreach ($persuratan as $surat) {
            $time = \Carbon\Carbon::createFromTimeString($surat->updated_at);
            $nomor_surat = sprintf("B-%04u/Un.05/III.4/TL.10/%02u/%u", $surat->nomor_surat, $time->month, $time->year);
            if ($surat->jenis_surat == 'izin-kunjungan') {
                $surat_simplified = array(
                    'id' => $surat->id,
                    'nomor_surat' => $nomor_surat,
                    'jenis_surat' => $surat->jenis->jenis_surat,
                    'identitas' => $surat->izin_kunjungan->program_studi,
                    'pemohon' => $surat->izin_kunjungan->semester.'-'.$surat->izin_kunjungan->kelas,
                    'waktu_readable' => $time->isoFormat('LLLL'),
                    'waktu' => $surat->updated_at
                );
            } else {
                $surat_simplified = array(
                    'id' => $surat->id,
                    'nomor_surat' => $nomor_surat,
                    'jenis_surat' => $surat->jenis->jenis_surat,
                    'identitas' => $surat->mahasiswa->nim,
                    'pemohon' => $surat->mahasiswa->nama,
                    'waktu_readable' => $time->isoFormat('LLLL'),
                    'waktu' => $surat->updated_at
                );
            }
            
            $letters[] = $surat_simplified;
        }

        return $letters;
    }

    public function detail($id)
    {
        $surat = Surat::find($id);
        // $batch = substr($surat->mahasiswa->nim, 1, 2);
        $batch = 16;
        $enroll = \Carbon\Carbon::createFromFormat('j n y', "1 7 $batch", config('app.timezone'));
        $time = \Carbon\Carbon::now();
        $roman_semester = int_to_roman($enroll->diffInMonths($time) / 6 + 1);
        $formatter = new \NumberFormatter(config('app.locale'), \NumberFormatter::SPELLOUT);
        $word_semester = $formatter->format(ceil($enroll->diffInMonths($time) / 6));
        $semester = "$roman_semester ($word_semester)";

        return view("admin.detail.$surat->jenis_surat", ['surat' => $surat, 'semester' => $semester]);
    }

    public function cetak($id)
    {
        $surat = Surat::find($id);
        $surat->status_surat = "Telah Diproses";
        $surat->save();
        $surat->perihal = html_entity_decode($surat->jenis->perihal);
        $time = \Carbon\Carbon::now();
        
        if ($surat->jenis_surat != 'izin-kunjungan') {
            $batch = substr($surat->mahasiswa->nim, 1, 2);
            $enroll = \Carbon\Carbon::createFromFormat('j n y', "1 7 $batch", config('app.timezone'));
            $roman_semester = int_to_roman($enroll->diffInMonths($time) / 6 + 1);
            $formatter = new \NumberFormatter(config('app.locale'), \NumberFormatter::SPELLOUT);
            $word_semester = $formatter->format(ceil($enroll->diffInMonths($time) / 6));
            $semester = "$roman_semester ($word_semester)";
            $surat->semester = $semester;
        } else {
            $surat->program_studi = \App\ProgramStudi::where('kode_prodi', $surat->izin_kunjungan->program_studi)->first()->program_studi;
            $waktu_kunjungan = new \Carbon\Carbon($surat->izin_kunjungan->tanggal_kunjungan.' '.$surat->izin_kunjungan->waktu_kunjungan, config('app.timezone'));
            $surat->tanggal_kunjungan = $waktu_kunjungan->isoFormat('dddd, DD MMMM YYYY');
            $surat->waktu_kunjungan = $waktu_kunjungan->isoFormat('HH:mm \\WIB');
        }

        $surat->nomor_surat = sprintf("B-%04u/Un.05/III.4/TL.10/%02u/%u", $surat->nomor_surat, $time->month, $time->year);
        $surat->tanggal_terbit = $time->isoFormat('LL');
        event(new \App\Events\SuratDiproses($surat));

        return view("surat.cetak.$surat->jenis_surat", ['surat' => $surat]);
    }

    public function sunting($id, Request $request)
    {
        $surat = Surat::find($id);

        $pemohon = "";

        if ($request->tipe_surat == "izin-kunjungan") {
            $detail = \App\IzinKunjungan::whereId($surat->surat)->update([
                'instansi_penerima' => $request->instansi_penerima,
                'alamat_instansi' => $request->alamat_instansi,
                'mata_kuliah' => $request->mata_kuliah,
                'program_studi' => $request->program_studi,
                'semester' => $request->semester,
                'kelas' => $request->kelas,
                'dosen_pengampu' => $request->dosen_pengampu,
                'tanggal_kunjungan' => \Carbon\Carbon::parseFromLocale($request->tanggal_kunjungan, config('app.locale'))->format("Y-m-d"),
                'waktu_kunjungan' => $request->waktu_kunjungan
            ]);
            $pemohon = "$request->program_studi $request->semester-$request->kelas";
        } else {
            $mahasiswa = Mahasiswa::updateOrCreate(
                ['nim' => $request->nim],
                ['nama' => $request->nama_mahasiswa,
                 'program_studi' => $request->program_studi,
                 'tanggal_lahir' => \Carbon\Carbon::parseFromLocale($request->tanggal_lahir, config('app.locale'))->format("Y-m-d"),
                 'alamat' => $request->alamat,
                 'no_telepon' => $request->no_telepon]
            );
            $pemohon = $mahasiswa->nim;
    
            switch ($request->tipe_surat) {
                case 'izin-observasi':
                    $mahasiswa->pembimbing_studi = $request->pembimbing_studi;
                    $mahasiswa->save();
                    $detail = \App\IzinObservasi::create([
                        'lokasi_observasi' => $request->lokasi_observasi,
                        'alamat_lokasi' => $request->alamat_lokasi,
                        'topik_skripsi' => $request->topik_skripsi
                    ]);
                    break;
                case 'izin-praktik':
                    $detail = \App\IzinPraktik::create([
                        'instansi_penerima' => $request->instansi_penerima,
                        'alamat_instansi' => $request->alamat_instansi,
                        'mata_kuliah' => $request->mata_kuliah,
                        'dosen_pengampu' => $request->dosen_pengampu    
                    ]);
                    break;
                case 'izin-riset':
                    $detail = \App\IzinRiset::create([
                        'lokasi_riset' => $request->lokasi_riset,
                        'alamat_lokasi' => $request->alamat_lokasi,
                        'judul_skripsi' => $request->judul_skripsi,
                        'pembimbing_1' => $request->pembimbing_1,
                        'pembimbing_2' => $request->pembimbing_2
                    ]);
                    break;
                case 'job-training':
                    $detail = \App\JobTraining::updateOrCreate(
                        ['instansi_penerima' => $request->instansi_penerima,
                         'alamat_instansi' => $request->alamat_instansi],
                        ['dosen_pembimbing' => $request->dosen_pembimbing]
                    );
                case 'permohonan-munaqasah':
                    $detail = \App\PermohonanMunaqasah::create([
                        'judul_skripsi' => $request->judul_skripsi,
                        'pembimbing_1' => $request->pembimbing_1,
                        'pembimbing_2' => $request->pembimbing_2
                    ]);
                    break;
                case 'pernyataan-masih-kuliah':
                    $pangol = explode(" - ", $request->pangkat_golongan);
                    $detail = \App\MasihKuliah::create([
                        'nama_orang_tua' => $request->nama_orang_tua,
                        'nip_orang_tua' => $request->nip_orang_tua,
                        'pangkat' => $pangol[0],
                        'golongan' => $pangol[1],
                        'instansi' => $request->instansi
                    ]);
                    break;
                case 'ppm':
                    $detail = \App\PPM::updateOrCreate(
                        ['instansi_penerima' => $request->instansi_penerima,
                         'alamat_instansi' => $request->alamat_instansi],
                        ['dosen_pembimbing' => $request->dosen_pembimbing]
    
                    );
                    break;
                case 'surat-keterangan':
                    $detail = \App\Keterangan::create([
                        'keperluan' => $request->keperluan
                    ]);
                    break;
                case 'permohonan-komprehensif':
                
                default:
                    break;
            }
        }
        

        $nomor = explode("/", $request->nomor_surat);
        $nomor_surat = explode("-", $nomor[0]);
        $surat->update([
            'nomor_surat' => intval($nomor_surat[1]),
            'pemohon' => $pemohon,
            'status_surat' => "Belum Diproses",
            'tanggal_terbit' => $request->tanggal_terbit
        ]);
        event(new \App\Events\SuratDisunting($surat));

        // switch ($request->tipe_surat) {
        //     case 'job-training':
        //     case 'ppm':
        //         \App\JobTrainingPPM::whereId($surat->surat)->update([
        //             'instansi_penerima' => $request->instansi_penerima,
        //             'alamat_instansi' => $request->alamat_instansi,
        //             'dosen_pembimbing' => $request->dosen_pembimbing
        //         ]);
        //         break;
        //     case 'izin-observasi':
        //         \App\IzinObservasi::whereId($surat->surat)->update([
        //             'dosen_pembimbing' => $request->dosen_pembimbing,
        //             'topik_penelitian' => $request->topik_penelitian
        //         ]);
        //         break;
        //     case 'izin-praktik':
        //         \App\IzinPraktik::whereId($surat->surat)->update([
        //             'mata_kuliah' => $request->mata_kuliah,
        //             'dosen_pengampu' => $request->dosen_pengampu
        //         ]);
        //         break;
        //     case 'izin-riset':
        //         \App\IzinRiset::whereId($surat->surat)->update([
        //             'topik_penelitian' => $request->topik_penelitian,
        //             'pembimbing_1' => $request->pembimbing_1,
        //             'pembimbing_2' => $request->pembimbing_2
        //         ]);
        //         break;
            
        //     default:
        //         break;
        // }

        return redirect()->route('beranda');
    }

    public function hapus($id)
    {
        $surat = Surat::find($id);

        switch ($surat->jenis_surat) {
            case 'izin-kunjungan':
                \App\IzinKunjungan::destroy($surat->surat);
                break;
            case 'permohonan-munaqasah':
                \App\PermohonanMunaqasah::destroy($surat->surat);
                break;
            case 'pernyataan-masih-kuliah':
                \App\MasihKuliah::destroy($surat->surat);
                break;
            case 'surat-keterangan':
                \App\Keterangan::destroy($surat->surat);
                break;
            case 'izin-observasi':
            case 'izin-praktik':
            case 'izin-riset':
            case 'job-training':
            case 'ppm':
            case 'permohonan-komprehensif':
            
            default:
        }
        
        Surat::destroy($id);

        return back();
    }
}
