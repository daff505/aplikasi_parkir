@extends('layouts.app')

@section('title', 'Riwayat Parkir Saya')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold text-white tracking-tight">Riwayat Parkir Saya</h2>
    <p class="text-slate-400 text-sm mt-1">Daftar transaksi parkir untuk kendaraan pribadi Anda (Pelanggan).</p>
</div>

<div class="card p-6 rounded-2xl">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-700 text-sm text-slate-400">
                    <th class="pb-3 font-medium">Plat Nomor</th>
                    <th class="pb-3 font-medium">Area Parkir</th>
                    <th class="pb-3 font-medium">Waktu Masuk</th>
                    <th class="pb-3 font-medium">Waktu Keluar</th>
                    <th class="pb-3 font-medium text-right">Tarif/Jam</th>
                    <th class="pb-3 font-medium text-right">Total Bayar</th>
                    <th class="pb-3 font-medium text-center">Status</th>
                    <th class="pb-3 font-medium text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-slate-300">
                @forelse($riwayat as $data)
                <tr class="border-b border-slate-700/50 hover:bg-slate-800/50 transition">
                    <td class="py-4 font-bold text-sky-400 uppercase tracking-widest">{{ $data->plat_nomor }}</td>
                    <td class="py-4">
                        <span class="px-2 py-1 rounded bg-slate-700 text-xs text-slate-300">{{ $data->nama_area }}</span>
                    </td>
                    <td class="py-4 text-slate-400 font-mono text-xs">
                        {{ date('d-m-Y H:i', strtotime($data->waktu_masuk)) }}
                    </td>
                    <td class="py-4 text-slate-400 font-mono text-xs">
                        {{ $data->waktu_keluar ? date('d-m-Y H:i', strtotime($data->waktu_keluar)) : '-' }}
                    </td>
                    <td class="py-4 text-right">Rp {{ number_format($data->tarif_per_jam, 0, ',', '.') }}</td>
                    <td class="py-4 text-right font-bold text-emerald-400">
                        {{ $data->biaya_total ? 'Rp ' . number_format($data->biaya_total, 0, ',', '.') : '-' }}
                    </td>
                    <td class="py-4 text-center">
                        @if($data->status == 'masuk')
                            <span class="px-2 py-1 rounded-full bg-amber-500/10 text-amber-500 text-[10px] uppercase font-bold border border-amber-500/20">Sedang Parkir</span>
                        @else
                            <span class="px-2 py-1 rounded-full bg-emerald-500/10 text-emerald-500 text-[10px] uppercase font-bold border border-emerald-500/20">Selesai Berbayar</span>
                        @endif
                    </td>
                    <td class="py-4 text-center">
                        @if($data->status != 'masuk')
                            <a href="{{ route('owner.riwayat.print', $data->id_parkir) }}" target="_blank" class="inline-flex items-center justify-center p-2 rounded-lg bg-slate-800 text-slate-400 hover:bg-emerald-500/20 hover:text-emerald-400 border border-slate-700 transition" title="Cetak Struk">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            </a>
                        @else
                            <span class="text-slate-600">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-12 text-center text-slate-500">
                        <div class="flex flex-col items-center gap-3">
                            <svg class="w-12 h-12 text-slate-700 font-thin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p>Tidak ada riwayat parkir yang ditemukan untuk kendaraan Anda.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($riwayat->hasPages())
    <div class="mt-6">
        {{ $riwayat->links() }}
    </div>
    @endif
</div>
@endsection
