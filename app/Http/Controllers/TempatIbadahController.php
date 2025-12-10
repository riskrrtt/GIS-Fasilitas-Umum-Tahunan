<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TempatIbadah;
use App\Desa;
use App\Agama;
use App\Sekolah;
use App\Pasar;

class TempatIbadahController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showTempatIbadah()
    {
        $tempatIbadah = TempatIbadah::get();

        // return response()->json([
        //     'message' => $sekolah
        // ]);
        return view('tempat-ibadah', compact('tempatIbadah'));
    }

    public function showAddTempatIbadah()
    {
        $desa = Desa::get();
        $agama = Agama::get();

        // Meneruskan data untuk peta
        $sekolah = Sekolah::get();
        $pasar = Pasar::get();
        $semuaTempatIbadah = TempatIbadah::get();

        return view('tempat-ibadah-tambah', compact('desa', 'agama', 'sekolah', 'pasar', 'semuaTempatIbadah'));
    }

    public function showEditTempatIbadah($tempatIbadah)
    {
        $desa = Desa::get();
        $agama = Agama::get();
        $tempatIbadah = TempatIbadah::where('id', $tempatIbadah)->get()->first();

        // Meneruskan data untuk peta
        $sekolah = Sekolah::get();
        $pasar = Pasar::get();
        $semuaTempatIbadah = TempatIbadah::get();

        return view('tempat-ibadah-edit', compact('desa', 'agama', 'tempatIbadah', 'sekolah', 'pasar', 'semuaTempatIbadah'));
    }

    public function createTempatIbadah(Request $request)
    {
        $tempatIbadah = TempatIbadah::create([
            'id_desa' => $request->desa,
            'id_jenis_potensi' => 3,
            'id_agama' => $request->agama,
            'nama' => $request->nama_tempat_ibadah,
            'alamat' => $request->alamat_tempat_ibadah,
            'lat' => $request->lat,
            'lng' => $request->lng
        ]);

        if ($tempatIbadah) {
            return redirect()->back()->with('done', 'Tempat ibadah berhasil di tambahkan');
        } else {
            return redirect()->back()->with('failed', 'Tempat ibadah gagal di tambahkan');
        }
    }

    public function deleteTempatIbadah($tempatIbadah)
    {
        $tempatIbadah = TempatIbadah::where('id', $tempatIbadah)->delete();
        if ($tempatIbadah > 0) {
            return redirect()->back()->with('done-delete', 'Tempat ibadah berhasil di hapus');
        } else {
            return redirect()->back()->with('failed-delete', 'Tempat ibadah gagal di hapus');
        }
    }

    public function updateTempatIbadah($tempatIbadah, Request $request)
    {
        $tempatIbadah = TempatIbadah::where('id', $tempatIbadah)->update([
            'id_desa' => $request->desa,
            'id_jenis_potensi' => 3,
            'id_agama' => $request->agama,
            'nama' => $request->nama_tempat_ibadah,
            'alamat' => $request->alamat_tempat_ibadah,
            'lat' => $request->lat,
            'lng' => $request->lng
        ]);
        ;

        if ($tempatIbadah > 0) {
            return redirect()->back()->with('done', 'Tempat ibadah berhasil di update');
        } else {
            return redirect()->back()->with('failed', 'Tempat ibadah gagal di update');
        }
    }
}
