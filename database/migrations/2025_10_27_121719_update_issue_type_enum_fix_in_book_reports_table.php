<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tukar enum kepada 'Fined' (ganti 'Fine')
        DB::statement("ALTER TABLE book_reports MODIFY issue_type ENUM('Damaged', 'Lost', 'Fined') NOT NULL");
    }

    public function down(): void
    {
        // Undur balik ke versi lama kalau perlu
        DB::statement("ALTER TABLE book_reports MODIFY issue_type ENUM('Damaged', 'Lost', 'Fine') NOT NULL");
    }
};
