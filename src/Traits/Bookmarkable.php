<?php

namespace CloudMyn\Bookmark\Traits;

use CloudMyn\Bookmark\Bookmark;
use CloudMyn\Bookmark\Events\Bookmarked;
use CloudMyn\Bookmark\Events\Unbookmarked;
use CloudMyn\Bookmark\Throwable\BookmarkException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Bookmarkable
{


    /**
     *  Method for find a bookmarker base on their class and id
     * 
     *  @param  string  $bookmarker_class
     *  @param  string  $bookmarker_id
     * 
     *  @return \Illuminate\Database\Eloquent\Collection
     */
    public function findBookmarker(string $bookmarker_class, int $bookmarker_id)
    {
        $bookmarker_id_name    =   config('bookmark.morph_name.bookmarker', 'bookmarker');

        $bookmarks = $this->bookmarks($bookmarker_class)->withPivotValue([
            "{$bookmarker_id_name}_id" => $bookmarker_id,
        ]);

        return $bookmarks->get();
    }

    /**
     *  Method for bookmark a bookmarkable object
     *
     *  @param  Illuminate\Database\Eloquent\Model  $bookmarker_object
     *  @return bool    return true if success otherwise false
     */
    public function bookmark(Model $bookmarker_object): bool
    {
        $this->validateMethod();

        $bookmark = new Bookmark();

        // $this referred to the model
        $bookmark->bookmarkable()->associate($this);
        $bookmark->bookmarker()->associate($bookmarker_object);

        event(new Bookmarked());

        return $bookmark->save();
    }

    /**
     *  Method for remove bookmarked model
     * 
     *  @param  string  $bookmarker_class
     *  @param  int     $bookmarker_id
     * 
     *  @return bool
     */
    public function unBookmark(string $bookmarker_class, int $bookmarker_id): bool
    {
        $this->validateMethod();

        $result = $this->bookmarks($bookmarker_class)->detach($bookmarker_id);

        if ($result >= 1) {
            event(new Unbookmarked());
            return true;
        }

        return false;
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
        $bookmarker     =   config('bookmark.morph_name.bookmarker', 'bookmarker');

        return $this->morphToMany($bookmarker_class, $bookmarkable, $tabel_name, null, "{$bookmarker}_id");
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
