<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('cascade')->after('receiver_id');
            $table->boolean('is_read')->default(false)->after('message');
            $table->foreignId('receiver_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropColumn(['group_id', 'is_read']);
        });
    }
};