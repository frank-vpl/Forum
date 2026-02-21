<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('viewer_hash')->nullable();
            $table->timestamps();

            $table->unique(['post_id', 'user_id', 'viewer_hash']);
            $table->index(['post_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_views');
    }
};
