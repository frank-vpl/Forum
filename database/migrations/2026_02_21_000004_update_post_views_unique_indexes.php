<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected function indexExists(string $table, string $index): bool
    {
        $database = DB::getDatabaseName();

        return DB::table('information_schema.STATISTICS')
            ->where('TABLE_SCHEMA', $database)
            ->where('TABLE_NAME', $table)
            ->where('INDEX_NAME', $index)
            ->exists();
    }

    public function up(): void
    {
        // Drop old 3-column unique if it exists (name is framework-convention)
        $oldIndex = 'post_views_post_id_user_id_viewer_hash_unique';
        if ($this->indexExists('post_views', $oldIndex)) {
            DB::statement('ALTER TABLE `post_views` DROP INDEX `'.$oldIndex.'`');
        }

        // Create new narrower unique indexes with explicit names (idempotent)
        Schema::table('post_views', function (Blueprint $table) {
            //
        });

        if (! $this->indexExists('post_views', 'post_views_post_id_user_id_unique')) {
            DB::statement('ALTER TABLE `post_views` ADD UNIQUE `post_views_post_id_user_id_unique` (`post_id`, `user_id`)');
        }
        if (! $this->indexExists('post_views', 'post_views_post_id_viewer_hash_unique')) {
            DB::statement('ALTER TABLE `post_views` ADD UNIQUE `post_views_post_id_viewer_hash_unique` (`post_id`, `viewer_hash`)');
        }
    }

    public function down(): void
    {
        if ($this->indexExists('post_views', 'post_views_post_id_user_id_unique')) {
            DB::statement('ALTER TABLE `post_views` DROP INDEX `post_views_post_id_user_id_unique`');
        }
        if ($this->indexExists('post_views', 'post_views_post_id_viewer_hash_unique')) {
            DB::statement('ALTER TABLE `post_views` DROP INDEX `post_views_post_id_viewer_hash_unique`');
        }

        // Recreate the old unique if not present (for rollback)
        if (! $this->indexExists('post_views', 'post_views_post_id_user_id_viewer_hash_unique')) {
            DB::statement('ALTER TABLE `post_views` ADD UNIQUE `post_views_post_id_user_id_viewer_hash_unique` (`post_id`, `user_id`, `viewer_hash`)');
        }
    }
};
