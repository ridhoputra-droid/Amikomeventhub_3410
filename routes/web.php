<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\CategoryController; 
use App\Models\Event;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// RUTE PUBLIK (LANDING PAGE & GUEST)
// ==========================================

Route::get('/', function () {
    $events = Event::with('category')->get(); 
    $partners = collect([]); 
    return view('welcome', compact('events', 'partners')); 
});

// TAMBAHKAN RUTE INI: Rute untuk melihat detail event berdasarkan ID
Route::get('/event-detail/{id}', function ($id) {
    // Mencari data event berdasarkan ID beserta kategorinya, jika tidak ketemu akan otomatis 404
    $event = Event::with('category')->findOrFail($id);
    
    // Mengembalikan view detail event (pastikan Anda sudah punya file event-detail.blade.php)
    return view('event-detail', compact('event'));
})->name('event.detail');


// ==========================================
// RUTE AUTENTIKASI ADMIN (LOGIN / LOGOUT)
// ==========================================

Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');


// ==========================================
// RUTE PROTECTIONS ADMIN (WAJIB LOGIN)
// ==========================================

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Halaman Utama Dashboard Admin
    Route::get('/dashboard', function() {
        $events = Event::with('category')->get(); 
        
        $totalPendapatan = 0;
        $totalTiketTerjual = 0;
        $totalEvent = $events->count(); 
        $transaksiPending = 0;
        $transaksiTerakhir = collect([]); 

        return view('admin.dashboard', compact(
            'events', 
            'totalPendapatan', 
            'totalTiketTerjual', 
            'totalEvent', 
            'transaksiPending', 
            'transaksiTerakhir'
        )); 
    })->name('dashboard');

    // Rute bayangan sidebar agar tidak crash
    Route::get('/transactions', function() {
        return "Halaman Data Transaksi (Modul Selanjutnya)";
    })->name('transactions');

    Route::get('/partners', function() {
        return "Halaman Manajemen Partners (Modul Selanjutnya)";
    })->name('partners');

    // Fitur Kategori
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Fitur Event
    Route::resource('events', EventController::class);
});