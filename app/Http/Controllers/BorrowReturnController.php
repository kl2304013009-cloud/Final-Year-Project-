<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Borrow;
use Illuminate\Http\Request;

class BorrowReturnController extends Controller
{
    public function index(Request $request)
    {
        $searchActive = $request->input('active_search'); // search untuk borrowed
        $searchReturned = $request->input('returned_search'); // search untuk returned

        $activeBorrows = Borrow::with(['book', 'student'])
            ->whereNull('returned_at')
            ->when($searchActive, function($query, $searchActive) {
                $query->whereHas('student', function($q) use ($searchActive) {
                    $q->where('name', 'like', "%{$searchActive}%");
                });
            })
            ->paginate(5, ['*'], 'active_page');

        $returnedBorrows = Borrow::with(['book', 'student'])
            ->whereNotNull('returned_at')
            ->when($searchReturned, function($query, $searchReturned) {
                $query->whereHas('student', function($q) use ($searchReturned) {
                    $q->where('name', 'like', "%{$searchReturned}%");
                });
            })
            ->orderByDesc('returned_at')
            ->paginate(5, ['*'], 'returned_page');

        return view('borrow_return', compact('activeBorrows', 'returnedBorrows'));
    }

    public function markAsReturned($id)
    {
        $borrow = Borrow::with('book')->findOrFail($id);

        if ($borrow->returned_at) {
            return redirect()->back()->with('error', 'Buku ini telah pun dikembalikan.');
        }

        // Set tarikh pulang
        $borrow->returned_at = now();

        // Kira denda (berhenti bila buku dah dipulangkan)
        $dueDate = \Carbon\Carbon::parse($borrow->return_date);
        $returnDate = \Carbon\Carbon::now();

        $lateDays = $returnDate->greaterThan($dueDate)
            ? $dueDate->diffInDays($returnDate) // ✅ betul arah
            : 0;

        $fine = $lateDays * 0.20; // RM0.20 sehari lewat
        $borrow->fine = $fine;
        $borrow->save();

        // Tambah balik stok buku bila dah dipulangkan
        if ($borrow->book) {
            $borrow->book->increment('quantity');
        }

        return redirect()->route('borrow_return')->with('success',
            $fine > 0
                ? "Buku dipulangkan lewat oleh {$lateDays} hari. Denda: RM" . number_format($fine, 2)
                : "Buku dipulangkan tepat pada masanya. Tiada denda dikenakan!"
        );
    }

    // Delete ALL returned borrows → semua denda dianggap dah bayar
    public function deleteAllReturned()
    {
        Borrow::whereNotNull('returned_at')->delete();

        return redirect()->back()->with('Berjaya', 'Semua rekod yang dikembalikan dipadamkan. Denda telah dibayar.');
    }

    // Delete satu rekod returned → pelajar dah bayar
    public function deleteReturnedOne($id)
    {
        $borrow = Borrow::whereNotNull('returned_at')->findOrFail($id);
        $borrow->delete();

        return redirect()->back()->with('Berjaya', 'Rekod dipadamkan. Denda dibayar.');
    }
}
