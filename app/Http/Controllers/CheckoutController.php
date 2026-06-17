<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman checkout.
     */
    public function create(Event $event)
    {
        return view('checkout', compact('event'));
    }

    /**
     * Menyimpan data transaksi ke database.
     */
    public function store(Request $request, Event $event)
    {
        // Validasi memastikan field wajib terisi
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        $orderId = 'EVT-' . strtoupper(Str::random(10));

        \App\Models\Transaction::create([
            'order_id'       => $orderId,
            'event_id'       => $event->id,
            'customer_name'  => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'total_price'    => $event->price,
            'status'         => 'Pending', // Sesuai default di database Anda
        ]);

        return redirect()->route('home')->with('success', 'Checkout berhasil! Kode: ' . $orderId);
    }
}