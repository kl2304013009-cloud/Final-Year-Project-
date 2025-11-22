<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE book_reports MODIFY issue_type ENUM('Damaged', 'Lost', 'Fine') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE book_reports MODIFY issue_type ENUM('Damaged', 'Lost') NOT NULL");
    }
};
