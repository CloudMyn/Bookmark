<?php

namespace CloudMyn\Bookmark\Traits;

use CloudMyn\Bookmark\Bookmark;
use CloudMyn\Bookmark\Throwable\BookmarkException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Bookmarker
{

    /**
     *  Method for bookmark a bookmarkable object
     *
     *  @param  Illuminate\Database\Eloquent\Model  $bookmarkable_class
     *  @return bool    return true if success otherwise false
     */
    public function bookmark(Model $bookmarkable_class): bool
    {
        $this->validateMethod();

        $bookmark = new Bookmark();

        $bookmark->bookmarkable()->associate($bookmarkable_class);
        // $this referred to the model
        $bookmark->bookmarker()->associate($this);

        return $bookmark->save();
    }

    /**
     *  This method for get all bookmark in related object
     *
     *  @param  string  $bookmarkable_class
     *  @param  bool    $queryReturn determine whether the return value should raw query or collectio model
     *
     *  @return \Illuminate\Database\Eloquent\Relations\MorphToMany|\Illuminate\Database\Eloquent\Collection
     */
    public function getBookmarks(string $bookmarkable_class, bool $queryReturn = false)
    {
        $query  =   $this->bookmarks($bookmarkable_class);

        return $queryReturn ? $query : $query->get();
    }

    /**
     *  Relational method
     *
     *  @param  string  bookmarkable_class
     *  @return Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    protected function bookmarks(string $bookmarkable_class): MorphToMany
    {
        $this->validateMethod();

        $tabel_name =   config('bookmark.table_name', 'bookmarks');
        $bookmarker =   config('bookmark.morph_name.bookmarker', 'bookmarker');

        return $this->morphToMany($bookmarkable_class, $bookmarker, $tabel_name);
    }

    /**
     *  Method for validating a methods
     *  this method will throwing an exception if failed
     */
    private function validateMethod()
    {
        if (($this instanceof Model) === false)
            throw new BookmarkException("Cannot calling this method outside of Illuminate\Database\Eloquent\Model");
    }


    // ...
}
