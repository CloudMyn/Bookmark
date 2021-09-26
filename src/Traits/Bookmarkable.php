<?php

namespace CloudMyn\Bookmark\Traits;

use CloudMyn\Bookmark\Bookmark;
use CloudMyn\Bookmark\Throwable\BookmarkException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Bookmarkable
{

    /**
     *  Method for bookmark a bookmarkable object
     *
     *  @param  Illuminate\Database\Eloquent\Model  $bookmarker_class
     *  @return bool    return true if success otherwise false
     */
    public function bookmark(Model $bookmarker_class): bool
    {
        $this->validateMethod();

        $bookmark = new Bookmark();

        // $this referred to the model
        $bookmark->bookmarkable()->associate($this);
        $bookmark->bookmarker()->associate($bookmarker_class);

        return $bookmark->save();
    }

    /**
     *  This method for get all bookmark in related object
     *
     *  @param  string  $bookmarker_class
     *  @param  bool    $queryReturn determine whether the return value should raw query or collection
     *
     *  @return \Illuminate\Database\Eloquent\Relations\MorphToMany|\Illuminate\Database\Eloquent\Collection
     */
    public function getBookmarkers(string $bookmarker_class, bool $queryReturn = false)
    {
        $query  =   $this->bookmarks($bookmarker_class);

        return $queryReturn ? $query : $query->get();
    }

    /**
     *  Relational method
     *
     *  @param  string  bookmarkable_class
     *  @return Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    protected function bookmarks(string $bookmarker_class): MorphToMany
    {
        $this->validateMethod();

        $tabel_name     =   config('bookmark.table_name', 'bookmarks');
        $bookmarkable   =   config('bookmark.morph_name.bookmarkable', 'bookmarkable');

        return $this->morphToMany($bookmarker_class, $bookmarkable, $tabel_name);
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
