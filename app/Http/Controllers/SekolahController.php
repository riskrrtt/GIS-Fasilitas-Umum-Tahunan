<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Desa;
use App\Jenjang;
use App\Sekolah;
use App\Pasar;
use App\TempatIbadah;

class SekolahController extends Controller
{
    public function showSekolah()
    {
        $sekolah = Sekolah::with('jenjang')->get();
        // return response()->json([
        //     'message' => $sekolah
        // ]);
        return view('sekolah', compact('sekolah'));
    }

    public function showAddSekolah()
    {
        $desa = Desa::get();
        $jenjang = Jenjang::get();
        // Meneruskan data untuk peta
        $semuaSekolah = Sekolah::get();
        $pasar = Pasar::get();
        $tempatIbadah = TempatIbadah::get();

        return view('sekolah-tambah', compact('desa', 'jenjang', 'semuaSekolah', 'pasar', 'tempatIbadah'));
    }

    public function showEditSekolah($sekolah)
    {
        $desa = Desa::get();
        $jenjang = Jenjang::get();
        $sekolah = Sekolah::where('id', $sekolah)->get()->first();

        // Meneruskan data untuk peta
        $semuaSekolah = Sekolah::get();
        $pasar = Pasar::get();
        $tempatIbadah = TempatIbadah::get();

        return view('sekolah-edit', compact('desa', 'jenjang', 'sekolah', 'semuaSekolah', 'pasar', 'tempatIbadah'));
    }

    public function createSekolah(Request $request)
    {
        $sekolah = Sekolah::create([
            'id_desa' => $request->desa,
            'id_jenjang' => $request->jenjang_sekolah,
            'id_jenis_potensi' => 1,
            'nama' => $request->nama_sekolah,
            'jenis_sekolah' => $request->jenis_sekolah,
            'alamat' => $request->alamat_sekolah,
            'lat' => $request->lat,
            'lng' => $request->lng
        ]);

        if ($sekolah) {
            return redirect()->back()->with('done', 'Sekolah berhasil di tambahkan');
        } else {
            return redirect()->back()->with('failed', 'Sekolah gagal di tambahkan');
        }
    }

    public function deleteSekolah($sekolah)
    {
        $sekolah = Sekolah::where('id', $sekolah)->delete();
        if ($sekolah > 0) {
            return redirect()->back()->with('done-delete', 'Sekolah berhasil di hapus');
        } else {
            return redirect()->back()->with('failed-delete', 'Sekolah gagal di hapus');
        }
    }

    public function updateSekolah($sekolah, Request $request)
    {
        $sekolah = Sekolah::where('id', $sekolah)->update([
            'id_desa' => $request->desa,
            'id_jenjang' => $request->jenjang_sekolah,
            'id_jenis_potensi' => 1,
            'nama' => $request->nama_sekolah,
            'jenis_sekolah' => $request->jenis_sekolah,
            'alamat' => $request->alamat_sekolah,
            'lat' => $request->lat,
            'lng' => $request->lng
        ]);
        ;

        if ($sekolah > 0) {
            return redirect()->back()->with('done', 'Sekolah berhasil di update');
        } else {
            return redirect()->back()->with('failed', 'Sekolah gagal di update');
        }
    }
}
