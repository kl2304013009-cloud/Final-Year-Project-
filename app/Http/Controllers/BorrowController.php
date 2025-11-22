<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Borrow;
use Illuminate\Support\Facades\Auth;

class BorrowController extends Controller
{
    /**
     * 📘 Papar semua buku (termasuk yang out of stock)
     */
    public function index(Request $request)
    {
        $books = Book::all();
        $selectedBook = $request->has('book') ? $books->firstWhere('id', $request->book) : null;

        // 📌 Kira total fine untuk student sekarang
        $studentId = Auth::guard('student')->id();
        $totalFine = Borrow::where('student_id', $studentId)->sum('fine');

        return view('borrow.index', compact('books', 'selectedBook', 'totalFine'));
    }
    
    /**
     * 🏷️ Simpan rekod pinjaman baru
     */
    public function store(Request $request, Book $book)
    {
        $request->validate([
            'borrow_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:borrow_date',
        ]);

        if ($book->quantity <= 0) {
            return back()->with('error', 'Sorry, this book is currently out of stock.');
        }

        Borrow::create([
            'student_id'  => Auth::guard('student')->id(),
            'book_id'     => $book->id,
            'borrow_date' => $request->borrow_date,
            'return_date' => $request->return_date,
        ]);

        $book->decrement('quantity');

        return redirect()->route('borrow.index')->with('success', 'Book borrowed successfully!');
    }

    /**
     * ✅ Tandakan buku sebagai dipulangkan + kira denda
     */
    public function markAsReturned($id)
    {
        $borrow = Borrow::with('book')->findOrFail($id);

        if ($borrow->returned_at) {
            return redirect()->back()->with('error', 'This book has already been returned.');
        }

        $borrow->returned_at = now();

        // 📅 Kira jumlah hari lewat (guna Carbon)
        $expected = \Carbon\Carbon::parse($borrow->return_date);
        $returned = \Carbon\Carbon::now();

        $daysLate = $returned->greaterThan($expected) ? $expected->diffInDays($returned) : 0;

        // 💰 Denda RM1 sehari lewat
        $fine = $daysLate > 0 ? $daysLate * 0.20 : 0;

        $borrow->fine = $fine;
        $borrow->save();

        // Tambah stok buku semula
        if ($borrow->book) {
            $borrow->book->increment('quantity');
        }

        return redirect()->back()->with(
            $fine > 0 ? 'error' : 'success',
            $fine > 0
                ? "Book returned late. Fine: RM{$fine}"
                : "Book returned on time. No fine charged!"
        );
    }

    /**
     * 👩‍🏫 Papar semua pinjaman untuk admin/guru
     */
    public function adminIndex()
    {
        $borrows = Borrow::with(['book', 'student'])
            ->orderBy('borrow_date', 'desc')
            ->get();

        return view('admin.borrow_return', compact('borrows'));
    }

    /**
     * 📜 Papar sejarah pinjaman pelajar (History)
     */
    public function pelajarHistory()
    {
        $studentId = Auth::guard('student')->id();

        $borrows = Borrow::with('book')
            ->where('student_id', $studentId)
            ->orderBy('borrow_date', 'desc')
            ->get();

        // Kira jumlah fine (cast kepada float untuk keselamatan)
        $totalFine = (float) $borrows->sum(function ($b) {
            return (float) ($b->fine ?? 0);
        });

        // Jika anda ingin jumlahkan hanya unpaid (contoh: paid ditandakan dengan trashed atau paid_at)
        $totalUnpaid = (float) $borrows->reduce(function ($carry, $b) {
            $fine = (float) ($b->fine ?? 0);
            $isPaid = method_exists($b, 'trashed') && $b->trashed(); // ubah ikut logik projek anda
            return $carry + (($fine > 0 && ! $isPaid) ? $fine : 0);
        }, 0);

        return view('pelajar.history', compact('borrows', 'totalFine', 'totalUnpaid'));
    }

}
