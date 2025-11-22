<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrow;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil senarai pinjaman aktif (belum dikembalikan) dengan pagination 6 rekod per halaman
        $activeBorrows = Borrow::with(['book', 'student'])
            ->whereNull('returned_at')
            ->orderByDesc('borrow_date')
            ->paginate(5); // <-- pagination 6 rekod

        return view('dashboard', compact('activeBorrows'));
    }
}
