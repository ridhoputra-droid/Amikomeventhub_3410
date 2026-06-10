@extends('layouts.admin')
@section('title', 'Edit Event - Admin')

@section('page_title', 'Edit Event')
@section('page_subtitle', 'Perbarui informasi data event dan berkas lampiran pendukung')
@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8">
        <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="col-span-2">
                    <label for="title" class="block text-sm font-bold text-slate-700 mb-2">Judul Event</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" 
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition @error('title') border-red-500 @enderror" required>
                    @error('title')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-bold text-slate-700 mb-2">Kategori</label>
                    <select name="category_id" id="category_id" 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none bg-white transition @error('category_id') border-red-500 @enderror" required>
                        @foreach(\App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date" class="block text-sm font-bold text-slate-700 mb-2">Tanggal & Waktu</label>
                    <input type="datetime-local" name="date" id="date" value="{{ old('date', date('Y-m-d\TH:i', strtotime($event->date))) }}" 
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition @error('date') border-red-500 @enderror" required>
                    @error('date')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="block text-sm font-bold text-slate-700 mb-2">Harga Tiket (Rp)</label>
                    <input type="number" name="price" id="price" value="{{ old('price', $event->price) }}" 
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition @error('price') border-red-500 @enderror" required>
                    @error('price')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stock" class="block text-sm font-bold text-slate-700 mb-2">Kuota Tiket (Stok)</label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', $event->stock) }}" 
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition @error('stock') border-red-500 @enderror" required>
                    @error('stock')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-2">
                    <label for="location" class="block text-sm font-bold text-slate-700 mb-2">Lokasi / Tempat</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $event->location) }}" 
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition @error('location') border-red-500 @enderror" required>
                    @error('location')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-2">
                    <label for="description" class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Lengkap</label>
                    <textarea name="description" id="description" rows="5" 
                              class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition @error('description') border-red-500 @enderror" required>{{ old('description', $event->description) }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-2 bg-slate-50 border border-slate-200 rounded-2xl p-6 space-y-4">
                    <div>
                        <span class="block text-sm font-bold text-slate-700 mb-2">Poster Berkas Aktif:</span>
                        @if($event->poster_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($event->poster_path))
                            <img src="{{ asset('storage/' . $event->poster_path) }}" alt="Poster Kegiatan" 
                                 class="w-44 h-auto rounded-xl shadow-sm border border-slate-100 object-cover aspect-[3/4]">
                        @else
                            <div class="w-44 h-52 bg-slate-200 text-slate-400 flex items-center justify-center text-xs font-semibold rounded-xl border">
                                Belum Ada Poster
                            </div>
                        @endif
                    </div>
                    
                    <div>
                        <label for="poster" class="block text-sm font-bold text-slate-700 mb-2">Ganti Poster Kegiatan (Opsional)</label>
                        <input type="file" name="poster" id="poster" accept="image/*" 
                               class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 cursor-pointer">
                        <p class="text-xs text-slate-400 mt-2">Kosongkan kolom berkas jika Anda tidak ingin melakukan perubahan pada poster lama.</p>
                        @error('poster')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="flex gap-4 border-t border-slate-100 pt-6">
                <a href="{{ route('admin.events') }}" class="px-6 py-3 border border-slate-200 text-slate-600 rounded-xl font-bold hover:bg-slate-50 transition">Batal</a>
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">Update Event</button>
            </div>
        </form>
    </div>
</div>
@endsection