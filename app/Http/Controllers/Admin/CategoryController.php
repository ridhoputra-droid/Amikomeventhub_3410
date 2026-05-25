<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // <-- 1. Tambahkan import Str di sini

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori (dengan fitur pencarian & pagination).
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $categories = Category::when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('admin.categories.index', compact('categories', 'search'));
    }

    /**
     * Menampilkan halaman form tambah kategori baru.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Menyimpan data kategori baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input dari user
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string'
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori ini sudah ada.',
            'name.max' => 'Nama kategori maksimal 255 karakter.'
        ]);

        // 2. Membuat slug otomatis dari nama kategori
        $data['slug'] = Str::slug($request->name);

        // Simpan ke database
        Category::create($data);

        // 3. Ubah redirect route ke 'admin.categories' agar sesuai dengan file blade Anda
        return redirect()->route('admin.categories')
            ->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail kategori.
     */
    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Menampilkan halaman form edit kategori.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Memperbarui data kategori di database.
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string'
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori ini sudah digunakan.',
            'name.max' => 'Nama kategori maksimal 255 karakter.'
        ]);

        // 4. Perbarui slug otomatis jika nama kategori diubah
        $data['slug'] = Str::slug($request->name);

        // Update data di database
        $category->update($data);

        return redirect()->route('admin.categories')
            ->with('success', 'Data kategori berhasil diperbarui.');
    }

    /**
     * Menghapus data kategori dari database.
     */
    public function destroy(Category $category)
    {
        if ($category->events()->exists()) {
            return redirect()->route('admin.categories')
                ->with('error', 'Kategori tidak bisa dihapus karena masih digunakan oleh beberapa event.');
        }

        $category->delete();

        return redirect()->route('admin.categories')
            ->with('success', 'Kategori berhasil dihapus secara permanen.');
    }
}