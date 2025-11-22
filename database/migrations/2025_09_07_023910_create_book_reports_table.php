<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_reports', function (Blueprint $table) {
            $table->id();
            $table->string('book_title');
            $table->enum('issue_type', ['Damaged', 'Lost', 'Fine']);
            $table->text('description');
            $table->foreignId('reported_by')->constrained('users')->onDelete('cascade'); // cikgu/admin id
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_reports');
    }
};
