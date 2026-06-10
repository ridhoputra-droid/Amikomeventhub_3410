<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Wajib di-import untuk menghapus berkas gambar

class EventController extends Controller
{
    // ... fungsi index, create, edit bawaan Anda tetap biarkan ...

    public function store(Request $request)
    {
        // 1. Validasi Input Ketat (Tugas Modul No. 4: Cegah Harga Tiket Minus)
        $validatedData = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'date'        => 'required|date',
            'location'    => 'required|string',
            'price'       => 'required|numeric|min:0', // Validasi minimal 0, harga tidak bisa minus
            'stock'       => 'required|integer|min:1',  // Stok minimal 1
            'poster'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Maksimal 2MB
        ]);

        // 2. Proses Simpan File Gambar ke Storage (Tugas Modul No. 1)
        if ($request->hasFile('poster')) {
            // Gambar otomatis masuk ke folder: storage/app/public/posters/
            $path = $request->file('poster')->store('posters', 'public');
            $validatedData['poster_path'] = $path;
        }

        Event::create($validatedData);

        return redirect()->route('events.index')->with('success', 'Event berhasil disimpan!');
    }

    public function update(Request $request, Event $event)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'date'        => 'required|date',
            'location'    => 'required|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:1',
            'poster'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Jika mengunggah gambar baru, hapus berkas gambar lama agar server hemat ruang
        if ($request->hasFile('poster')) {
            if ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) {
                Storage::disk('public')->delete($event->poster_path);
            }
            $path = $request->file('poster')->store('posters', 'public');
            $validatedData['poster_path'] = $path;
        }

        $event->update($validatedData);

        return redirect()->route('events.index')->with('success', 'Event berhasil diperbarui!');
    }

    public function destroy(Event $event)
    {
        // Tugas Mandiri Modul No. 3: Hapus gambar di storage ketika event dihapus
        if ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) {
            Storage::disk('public')->delete($event->poster_path);
        }

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event dan poster berhasil dihapus!');
    }
}