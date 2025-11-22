<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'student_id',
        'borrow_date',
        'return_date',
        'returned_at',
        'fine',
    ];

    // Automatik convert ke Carbon date object
    protected $casts = [
        'borrow_date' => 'datetime',
        'return_date' => 'datetime',
        'returned_at' => 'datetime',
        'fine' => 'float',
    ];

    // Hubungan ke model Book
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Hubungan ke model Student
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
