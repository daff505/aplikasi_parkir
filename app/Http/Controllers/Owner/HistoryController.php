<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $riwayat = DB::table('tb_transaksi')
            ->join('tb_kendaraan', 'tb_transaksi.id_kendaraan', '=', 'tb_kendaraan.id_kendaraan')
            ->join('tb_area_parkir', 'tb_transaksi.id_area', '=', 'tb_area_parkir.id_area')
            ->join('tb_tarif', 'tb_transaksi.id_tarif', '=', 'tb_tarif.id_tarif')
            ->select(
                'tb_transaksi.*', 
                'tb_kendaraan.plat_nomor', 
                'tb_area_parkir.nama_area', 
                'tb_area_parkir.lokasi',
                'tb_tarif.tarif_per_jam'
            )
            ->where('tb_kendaraan.id_user', $userId)
            ->orderBy('tb_transaksi.waktu_masuk', 'desc')
            ->paginate(10);

        return view('owner.riwayat', compact('riwayat'));
    }

    public function cetak($id)
    {
        $userId = auth()->id();
        
        // Pastikan transaksi ini milik user tersebut
        $transaksi = DB::table('tb_transaksi')
            ->join('tb_kendaraan', 'tb_transaksi.id_kendaraan', '=', 'tb_kendaraan.id_kendaraan')
            ->where('tb_kendaraan.id_user', $userId)
            ->where('tb_transaksi.id_parkir', $id)
            ->select('tb_transaksi.*')
            ->first();

        if (!$transaksi) {
            return back()->with('error', 'Struk tidak ditemukan atau Anda tidak punya akses.');
        }

        // Ambil data struk LENGKAP dengan join ke transaksi dan kendaraan
        $struk = DB::table('tb_struk')
            ->join('tb_transaksi', 'tb_struk.id_transaksi', '=', 'tb_transaksi.id_parkir')
            ->join('tb_kendaraan', 'tb_transaksi.id_kendaraan', '=', 'tb_kendaraan.id_kendaraan')
            ->join('tb_user', 'tb_struk.dicetak_oleh', '=', 'tb_user.id_user')
            ->where('tb_struk.id_transaksi', $id)
            ->select(
                'tb_struk.*', 
                'tb_transaksi.nomor_tiket',
                'tb_transaksi.waktu_masuk',
                'tb_transaksi.waktu_keluar',
                'tb_transaksi.durasi_jam',
                'tb_transaksi.biaya_total',
                'tb_transaksi.metode_bayar',
                'tb_kendaraan.plat_nomor',
                'tb_user.nama_lengkap as nama_petugas'
            )
            ->first();

        if (!$struk) {
            return back()->with('error', 'Data struk fisik belum tersedia. Pastikan kendaraan sudah keluar.');
        }

        return view('transaksi.struk', compact('struk'));
    }
}
