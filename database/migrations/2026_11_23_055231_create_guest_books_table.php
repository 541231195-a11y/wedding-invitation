<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('guest_books', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('invitation_id');
        $table->string('code')->unique()->nullable(); // Kode unik QR/Undangan tamu
        $table->string('name');
        $table->string('group_name')->nullable();
        $table->string('phone')->nullable();
        $table->string('country_code')->default('62');
        $table->string('attendance')->nullable(); // Hadir / Tidak Hadir
        $table->integer('guest')->default(1); // Jumlah tamu
        $table->text('comment')->nullable();
        $table->text('custom_feedback')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_books');
    }
};
