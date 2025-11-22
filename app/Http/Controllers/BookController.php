<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Hash;

class BookController extends Controller
{
    public function index(Request $request)
{
    if ($request->has('reset')) {
        return redirect()->route('books.index');
    }

    $search = $request->input('search');
    $alphabet = $request->input('alphabet');
    $category = $request->input('category');

    $books = Book::query()
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        })
        ->when($alphabet, function ($query, $alphabet) {
            $query->where('title', 'like', "{$alphabet}%");
        })
        ->when($category, function ($query, $category) {
            $query->where('category', $category);
        })
        ->orderBy('title', 'asc')
        ->paginate(7)
        ->appends([
            'search' => $search,
            'alphabet' => $alphabet,
            'category' => $category,
        ]);

    // ✅ Ambil kategori unik terus dari DB
    $categories = Book::select('category')
        ->distinct()
        ->orderBy('category', 'asc')
        ->pluck('category');

    return view('books.index', compact('books', 'search', 'alphabet', 'category', 'categories'));
}

    public function create()
    {
        $categories = Book::select('category')->distinct()->pluck('category')->filter()->sort()->values();
        return view('books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'author'    => 'required|string|max:255',
            'isbn'      => 'required|string|max:100|unique:books',
            'year'      => 'required|integer|min:1000|max:' . date('Y'),
            'category'  => 'required|string|max:255',
            'quantity'  => 'required|integer|min:1',
        ]);

        Book::create($request->all());

        return redirect()->route('books.index')->with('success', 'Book added successfully!');
    }

    public function edit(Book $book)
    {
        $categories = Book::select('category')->distinct()->pluck('category')->filter()->sort()->values();
        return view('books.edit', compact('book', 'categories'));
    }
    
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'author'    => 'required|string|max:255',
            'isbn'      => 'required|string|max:100|unique:books,isbn,' . $book->id,
            'year'      => 'required|integer|min:1000|max:' . date('Y'),
            'category'  => 'required|string|max:255',
            'quantity'  => 'required|integer|min:1',
        ]);

        $book->update($request->all());

        return redirect()->route('books.index')->with('success', 'Book updated successfully!');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Book deleted successfully!');
    }

    // ✅ Autocomplete function (this is what your form uses)
    public function autocomplete(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response()->json([]);
        }

        // Ambil 10 tajuk buku yang sama dengan apa yang ditaip
        $books = Book::where('title', 'like', "%{$query}%")
                    ->orderBy('title', 'asc')
                    ->limit(10)
                    ->pluck('title');

        return response()->json($books);
    }

    public function importExcel(Request $request)
{
    set_time_limit(300); // bagi masa lebih untuk Excel besar

    $request->validate([
        'excel_file' => 'required|file|mimes:xlsx,xls'
    ]);

    $file = $request->file('excel_file');

    // Load semua sheet dalam fail Excel
    $spreadsheet = IOFactory::load($file->getPathname());
    $sheetNames = $spreadsheet->getSheetNames();

    $totalImported = 0;

    foreach ($sheetNames as $sheetName) {
        $sheet = $spreadsheet->getSheetByName($sheetName);
        $allRows = $sheet->toArray();

        // Skip sheet kosong atau tiada data
        if (count($allRows) < 2) continue;

        // Row ke-1 = tajuk / header, data bermula row ke-2
        for ($i = 1; $i < count($allRows); $i++) {
            $row = $allRows[$i];

            // Pastikan semua data penting ada
            if (!isset($row[0], $row[1], $row[2], $row[3], $row[4], $row[5])) continue;

            $title     = trim($row[0]);
            $author    = trim($row[1]);
            $isbn      = trim($row[2]);
            $year      = (int) trim($row[3]);
            $category  = trim($row[4]);
            $quantity  = (int) trim($row[5]);

            if (empty($title) || empty($isbn)) continue;

            // Elak duplicate berdasarkan ISBN
            if (Book::where('isbn', $isbn)->exists()) continue;

            Book::create([
                'title'    => $title,
                'author'   => $author,
                'isbn'     => $isbn,
                'year'     => $year ?: date('Y'),
                'category' => $category ?: 'Uncategorized',
                'quantity' => $quantity ?: 1,
            ]);

            $totalImported++;
        }
    }

    return redirect()->route('books.index')
        ->with('success', "{$totalImported} books imported successfully from Excel!");
}
}
