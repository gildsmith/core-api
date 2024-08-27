<?php

use Gildsmith\HubApi\Models\Language;
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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5);
        });

        /**
         * List of officially assigned two-letter language codes.
         *
         * @see https://en.wikipedia.org/wiki/List_of_ISO_639_language_codes#Table_of_all_possible_two_letter_codes
         */
        Language::insert(require dirname(__DIR__).'/datasets/languages.php');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
