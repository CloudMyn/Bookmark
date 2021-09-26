<?php

namespace CloudMyn\Bookmark;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Bookmark extends Model
{

    /**
     *  Method for define a ralaated table for this model
     *
     *  @return  string tabel_name
     */
    public function getTable()
    {
        return  config('bookmark.table_name', 'bookmarks');
    }

    public function bookmarkable(): MorphTo
    {
        $bookmarkable = config('bookmark.morph_name.bookmarkable', 'bookmarkable');

        return $this->morphTo($bookmarkable, "{$bookmarkable}_type", "{$bookmarkable}_id");
    }

    public function bookmarker(): MorphTo
    {
        $bookmarker = config('bookmark.morph_name.bookmarker', 'bookmarker');

        return $this->morphTo($bookmarker, "{$bookmarker}_type", "{$bookmarker}_id");
    }

    // ...
}
