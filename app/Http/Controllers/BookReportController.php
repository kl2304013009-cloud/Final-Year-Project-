<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookReport;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class BookReportController extends Controller
{
    // 📋 List semua report
    public function index()
    {
        $reports = BookReport::latest()->get();
        return view('report.index', compact('reports'));
    }

    // 📝 Form tambah report
    public function create()
    {
        return view('report.create');
    }

    // 💾 Simpan report
    public function store(Request $request)
    {
        $request->validate([
            'book_title' => 'required|string|max:255',
            'issue_type' => 'required|in:Damaged,Lost,Fined',
            'description' => 'required|string',
        ]);

        BookReport::create([
            'book_title' => $request->book_title,
            'issue_type' => $request->issue_type,
            'description' => $request->description,
            'reported_by' => Auth::guard('student')->id() ?? Auth::id(),
        ]);

        return redirect()->route('report.index')->with('success', 'Report submitted.');
    }

    // ✏️ Form edit report
    public function edit(BookReport $report)
    {
        return view('report.edit', ['bookReport' => $report]);
    }

    // 🔄 Update report
    public function update(Request $request, BookReport $report)
    {
        $request->validate([
            'book_title' => 'required|string|max:255',
            'issue_type' => 'required|in:Damaged,Lost,Fined',
            'description' => 'required|string',
        ]);

        $report->update($request->all());

        return redirect()->route('report.index')->with('success', 'Report updated.');
    }

    // ❌ Delete report
    public function destroy(BookReport $report)
    {
        $report->delete();
        return redirect()->route('report.index')->with('success', 'Report deleted.');
    }

    public function downloadAllPDF()
    {
        $reports = BookReport::latest()->get();

        if ($reports->isEmpty()) {
            return redirect()->route('report.index')->with('success', 'No reports to download.');
        }

        $totalDamaged = $reports->where('issue_type', 'Damaged')->count();
        $totalLost = $reports->where('issue_type', 'Lost')->count();
        $totalFined = $reports->where('issue_type', 'Fined')->count();

        $pdf = Pdf::loadView('report.pdf_all', compact('reports', 'totalDamaged', 'totalLost', 'totalFined'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Library_Reports_' . now()->format('Ymd_His') . '.pdf');
    }
}
