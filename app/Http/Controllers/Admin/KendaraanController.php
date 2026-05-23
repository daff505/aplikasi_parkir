<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\User;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Kendaraan::with('user');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('plat_nomor', 'like', "%{$search}%")
                  ->orWhere('pemilik', 'like', "%{$search}%")
                  ->orWhere('merk', 'like', "%{$search}%");
            });
        }

        $kendaraans = $query->orderBy('created_at', 'desc')->get();

        return view('admin.kendaraan.index', compact('kendaraans', 'search'));
    }

    public function create()
    {
        $kendaraan = new Kendaraan();
        $users = User::where('status_aktif', 1)->orderBy('nama_lengkap')->get();
        return view('admin.kendaraan.form', compact('kendaraan', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plat_nomor'      => 'required|string|max:15|unique:tb_kendaraan,plat_nomor',
            'jenis_kendaraan' => 'required|in:motor,mobil,truk,lainnya',
            'warna'           => 'nullable|string|max:20',
            'merk'            => 'nullable|string|max:50',
            'pemilik'         => 'nullable|string|max:100',
            'id_user'         => 'nullable|exists:tb_user,id_user',
        ], [
            'plat_nomor.unique' => 'Plat nomor ini sudah terdaftar di sistem.',
        ]);

        Kendaraan::create([
            'plat_nomor'      => strtoupper($request->plat_nomor),
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'warna'           => $request->warna,
            'merk'            => $request->merk,
            'pemilik'         => $request->pemilik,
            'id_user'         => $request->id_user ?: null,
        ]);

        return redirect()->route('admin.kendaraan.index')->with('success', 'Data kendaraan berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        $users = User::where('status_aktif', 1)->orderBy('nama_lengkap')->get();
        return view('admin.kendaraan.form', compact('kendaraan', 'users'));
    }

    public function update(Request $request, string $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        $request->validate([
            'plat_nomor'      => 'required|string|max:15|unique:tb_kendaraan,plat_nomor,' . $id . ',id_kendaraan',
            'jenis_kendaraan' => 'required|in:motor,mobil,truk,lainnya',
            'warna'           => 'nullable|string|max:20',
            'merk'            => 'nullable|string|max:50',
            'pemilik'         => 'nullable|string|max:100',
            'id_user'         => 'nullable|exists:tb_user,id_user',
        ], [
            'plat_nomor.unique' => 'Plat nomor ini sudah digunakan kendaraan lain.',
        ]);

        $kendaraan->update([
            'plat_nomor'      => strtoupper($request->plat_nomor),
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'warna'           => $request->warna,
            'merk'            => $request->merk,
            'pemilik'         => $request->pemilik,
            'id_user'         => $request->id_user ?: null,
        ]);

        return redirect()->route('admin.kendaraan.index')->with('success', 'Data kendaraan berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        // Cek apakah kendaraan masih dalam transaksi aktif
        $transaksiAktif = \Illuminate\Support\Facades\DB::table('tb_transaksi')
            ->where('id_kendaraan', $id)
            ->where('status', 'masuk')
            ->count();

        if ($transaksiAktif > 0) {
            return back()->with('error', 'Kendaraan ini masih dalam area parkir (status masuk). Tidak bisa dihapus.');
        }

        $kendaraan->delete();
        return redirect()->route('admin.kendaraan.index')->with('success', 'Data kendaraan berhasil dihapus!');
    }
}
