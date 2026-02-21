<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            if (! Schema::hasColumn('comments', 'reply_to_comment_id')) {
                $table->foreignId('reply_to_comment_id')->nullable()->after('parent_id')->constrained('comments')->cascadeOnDelete();
                $table->index(['reply_to_comment_id']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            if (Schema::hasColumn('comments', 'reply_to_comment_id')) {
                $table->dropConstrainedForeignId('reply_to_comment_id');
            }
        });
    }
};
