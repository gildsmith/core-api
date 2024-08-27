<?php

declare(strict_types=1);

use Gildsmith\HubApi\Models\Channel;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $channel = Channel::defaultBlueprint();
        $channel->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Channel::find(1)?->delete();
    }
};
