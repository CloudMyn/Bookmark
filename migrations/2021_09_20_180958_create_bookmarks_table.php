<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookmarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table_name = config('bookmark.table_name', 'bookmarks');

        Schema::create($table_name, function (Blueprint $table) {

            $bookmarker_morph_name      =   config('bookmark.morph_name.bookmarker', 'bookmarker');
            $bookmarkable_morph_name    =   config('bookmark.morph_name.bookmarkable', 'bookmarkable');

            $table->id();
            $table->morphs($bookmarker_morph_name);
            $table->morphs($bookmarkable_morph_name);
            $table->timestamps();

            $table->unique([
                "{$bookmarker_morph_name}_id", "{$bookmarker_morph_name}_type",
                "{$bookmarkable_morph_name}_id", "{$bookmarkable_morph_name}_type"
            ], "cloudmyn_bookmarks");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookmarks');
    }
}
