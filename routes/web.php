<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\BookReportController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\BorrowReturnController;

Route::get('student/login', [StudentAuthController::class, 'showLoginForm'])->name('student.login');
Route::post('student/login', [StudentAuthController::class, 'login'])->name('student.login.submit');
Route::post('student/logout', [StudentAuthController::class, 'logout'])->name('student.logout');

Route::get('/', function () {
    return view('welcome');
});

// ================================
// 📘 ROUTE UNTUK PELAJAR (STUDENT)
// ================================
Route::middleware(['auth:student'])->group(function () {
    Route::get('/students/dashboard', function () {
        return view('pelajar.dashboard');
    })->name('students.dashboard');

    Route::get('/borrow', [BorrowController::class, 'index'])->name('borrow.index');
    Route::post('/borrow/{book}', [BorrowController::class, 'store'])->name('borrow.store');

    // 📜 History page untuk pelajar
    Route::get('/pelajar/history', [BorrowController::class, 'pelajarHistory'])->name('pelajar.history');
});

// ================================
// 📚 ROUTE UNTUK ADMIN / GURU
// ================================
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Books
    Route::resource('books', BookController::class)->except(['show']);
    Route::post('/books/import', [BookController::class, 'importExcel'])->name('books.import');

    // Students
    Route::delete('/students/delete-all', [StudentController::class, 'deleteAll'])->name('students.deleteAll');
    Route::post('/students/import', [StudentController::class, 'importExcel'])->name('students.import');
    Route::resource('students', StudentController::class)->except(['show']);

    // Reports
    Route::resource('report', BookReportController::class)->except(['show']);
    Route::get('/books/autocomplete', [BookController::class, 'autocomplete'])->name('books.autocomplete');
    Route::get('/report/download-all', [BookReportController::class, 'downloadAllPDF'])->name('report.downloadAll');

    // Borrow & Return page (admin / cikgu)
    Route::get('/borrow-return', [BorrowReturnController::class, 'index'])->name('borrow_return');
    Route::post('/borrow-return/{id}/return', [BorrowReturnController::class, 'markAsReturned'])->name('borrow_return_mark');
    Route::delete('/borrow/delete-all', [BorrowReturnController::class, 'deleteAllActive'])->name('borrow_delete_all');
    Route::delete('/borrow/returned-delete-all', [BorrowReturnController::class, 'deleteAllReturned'])->name('returned_delete_all');
    Route::delete('/borrow/returned-delete/{id}', [BorrowReturnController::class, 'deleteReturnedOne'])->name('returned_delete_one');
});
