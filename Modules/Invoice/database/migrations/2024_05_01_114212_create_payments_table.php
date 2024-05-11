<?php

use Illuminate\Auth\Passwords\TokenRepositoryInterface;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')
            ->nullable()
            ->constrained('invoices')
            ->cascadeOnDelete();
            $table->unsignedInteger('amount');
            $table->string('token')->nullable()->unique();
            $table->string('driver');
            $table->string('tracking_code')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
