<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class AdminCustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $customers = $query->latest()->get();

        return view('admin.customer.index', compact('customers'));
    }

    public function show($id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);

        $pesanans = Pesanan::where('user_id', $customer->id)
            ->latest()
            ->get();

        return view('admin.customer.show', compact('customer', 'pesanans'));
    }
}