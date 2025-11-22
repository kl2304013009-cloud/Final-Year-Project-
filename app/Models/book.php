<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'year',
        'category',
        'quantity',
    ];

    // Relationship dengan table borrows
    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    // Optional: method untuk check kalau buku sedang dipinjam
    public function isBorrowed()
    {
        return $this->borrows()->whereNull('returned_at')->exists();
    }
}
