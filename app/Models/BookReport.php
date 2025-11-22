<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_title',
        'issue_type',
        'description',
        'reported_by',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
