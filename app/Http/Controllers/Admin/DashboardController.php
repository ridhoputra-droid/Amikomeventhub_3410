<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Transaction;

class DashboardController extends Controller
{
   public function index() {
    // 1. Perbaikan: Ubah 'total_price' menjadi 'total_amount' sesuai migrasi aslimu
    $totalPendapatan = Transaction::sum('total_amount');
    
    $totalTiketTerjual = Transaction::count();
    $totalEvent = Event::count();
    
    // 2. Perbaikan: Ubah 'status' menjadi 'payment_status' dan 'Pending' menjadi 'pending'
    $transaksiPending = Transaction::where('payment_status', 'pending')->count();
    
    $transaksiTerakhir = Transaction::with('event')->orderByDesc('created_at')->take(5)->get();
    $events = Event::with('category')->orderByDesc('created_at')->take(3)->get();

    return view('admin.dashboard', compact(
        'totalPendapatan',
        'totalTiketTerjual',
        'totalEvent',
        'transaksiPending',
        'transaksiTerakhir',
        'events'
    ));
}

    public function indexAdmin() 
    {
        return view('admin.events');
    }

    public function transactionsAdmin() 
    {
        return view('admin.transactions');
    }
}