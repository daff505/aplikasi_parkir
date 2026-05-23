@extends('layouts.app')

@section('title', $kendaraan->exists ? 'Edit Kendaraan' : 'Tambah Kendaraan Baru')

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.kendaraan.index') }}" class="text-sky-400 hover:text-sky-300 text-sm flex items-center mb-4 transition">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Data Kendaraan
    </a>
    <h2 class="text-2xl font-bold text-white tracking-tight">
        {{ $kendaraan->exists ? 'Edit Kendaraan: ' . $kendaraan->plat_nomor : 'Tambah Kendaraan Baru' }}
    </h2>
    <p class="text-slate-400 text-sm mt-1">Lengkapi formulir data kendaraan di bawah ini.</p>
</div>

@if($errors->any())
<div class="bg-red-500/10 border border-red-500/30 text-red-400 p-4 rounded-xl mb-6 text-sm">
    <p class="font-bold mb-1">Terdapat kesalahan pada inputan:</p>
    <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card p-6 md:p-8 rounded-2xl w-full">
    <form action="{{ $kendaraan->exists ? route('admin.kendaraan.update', $kendaraan->id_kendaraan) : route('admin.kendaraan.store') }}" method="POST">
        @csrf
        @if($kendaraan->exists)
            @method('PUT')
        @endif

        <div class="space-y-6">
            {{-- Row 1: Plat Nomor + Jenis --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="plat_nomor" class="block text-sm font-medium text-slate-300 mb-2">
                        Plat Nomor <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="plat_nomor" id="plat_nomor"
                        value="{{ old('plat_nomor', $kendaraan->plat_nomor) }}"
                        required placeholder="Contoh: B 1234 ABC"
                        style="text-transform: uppercase;"
                        class="w-full bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3 text-white font-mono font-bold tracking-widest focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                    @error('plat_nomor') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="jenis_kendaraan" class="block text-sm font-medium text-slate-300 mb-2">
                        Jenis Kendaraan <span class="text-red-400">*</span>
                    </label>
                    <select name="jenis_kendaraan" id="jenis_kendaraan" required
                        class="w-full bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="motor"   {{ old('jenis_kendaraan', $kendaraan->jenis_kendaraan) == 'motor'   ? 'selected' : '' }}>🏍️ Motor</option>
                        <option value="mobil"   {{ old('jenis_kendaraan', $kendaraan->jenis_kendaraan) == 'mobil'   ? 'selected' : '' }}>🚗 Mobil</option>
                        <option value="truk"    {{ old('jenis_kendaraan', $kendaraan->jenis_kendaraan) == 'truk'    ? 'selected' : '' }}>🚛 Truk</option>
                        <option value="lainnya" {{ old('jenis_kendaraan', $kendaraan->jenis_kendaraan) == 'lainnya' ? 'selected' : '' }}>🚌 Lainnya</option>
                    </select>
                    @error('jenis_kendaraan') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Row 2: Merek + Warna --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="merk" class="block text-sm font-medium text-slate-300 mb-2">Merek Kendaraan</label>
                    <input type="text" name="merk" id="merk"
                        value="{{ old('merk', $kendaraan->merk) }}"
                        placeholder="Contoh: Honda, Toyota, Yamaha"
                        class="w-full bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                    @error('merk') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="warna" class="block text-sm font-medium text-slate-300 mb-2">Warna Kendaraan</label>
                    <input type="text" name="warna" id="warna"
                        value="{{ old('warna', $kendaraan->warna) }}"
                        placeholder="Contoh: Merah, Hitam, Putih"
                        class="w-full bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                    @error('warna') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Row 3: Pemilik + User --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="pemilik" class="block text-sm font-medium text-slate-300 mb-2">Nama Pemilik</label>
                    <input type="text" name="pemilik" id="pemilik"
                        value="{{ old('pemilik', $kendaraan->pemilik) }}"
                        placeholder="Nama lengkap pemilik kendaraan"
                        class="w-full bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                    @error('pemilik') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="id_user" class="block text-sm font-medium text-slate-300 mb-2">Kaitkan ke Akun User <span class="text-slate-500">(Opsional)</span></label>
                    <select name="id_user" id="id_user"
                        class="w-full bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                        <option value="">-- Tamu / Umum (Tidak dikaitkan) --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id_user }}"
                                {{ old('id_user', $kendaraan->id_user) == $user->id_user ? 'selected' : '' }}>
                                {{ $user->nama_lengkap }} ({{ $user->username }} - {{ ucfirst($user->role) }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_user') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="bg-sky-500/10 border border-sky-500/20 text-sky-400 text-xs p-3 rounded-lg flex items-start gap-2">
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Plat nomor akan otomatis diubah ke <strong>huruf kapital</strong>. Kendaraan tamu yang tidak terdaftar di akun manapun tetap bisa dicatat dengan mengosongkan field "Kaitkan ke Akun User".</span>
            </div>

            <hr class="border-slate-700/50">

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.kendaraan.index') }}" class="px-6 py-3 rounded-xl text-slate-400 hover:text-white border border-slate-700 hover:border-slate-600 transition font-medium">
                    Batal
                </a>
                <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 px-6 py-3 rounded-xl text-white font-bold transition">
                    {{ $kendaraan->exists ? 'Simpan Perubahan' : 'Daftarkan Kendaraan' }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
