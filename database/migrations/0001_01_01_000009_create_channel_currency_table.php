<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('channel_currency', function (Blueprint $table) {
            $table->foreignId('channel_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('currency_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->unique(['channel_id', 'currency_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channel_currency');
    }
};
