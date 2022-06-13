<?php

namespace CloudMyn\Bookmark\Traits;

use CloudMyn\Bookmark\Bookmark;
use CloudMyn\Bookmark\Events\Bookmarked;
use CloudMyn\Bookmark\Events\UnBookmark;
use CloudMyn\Bookmark\Throwable\BookmarkException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Bookmarker
{

    /**
     *  Method for find a bookmarkable base on their class and id
     * 
     *  @param  string  $bookmarkable_class
     *  @param  string  $bookmarkable_id
     * 
     *  @return \Illuminate\Database\Eloquent\Collection
     */
    public function findBookmarkable(string $bookmarkable_class, int $bookmarkable_id)
    {
        $bookmarkable_id_name    =   config('bookmark.morph_name.bookmarkable', 'bookmarkable');

        $bookmarks = $this->bookmarks($bookmarkable_class)->withPivotValue([
            "{$bookmarkable_id_name}_id" => $bookmarkable_id,
        ]);

        return $bookmarks->get();
    }

    /**
     *  Method for checking whether the object is bookmarked or not
     * 
     *  @param  string  $bookmarkable_class
     *  @param  string  $bookmarkable_id
     * 
     *  @return bool
     */
    public function isBookmarked(string $bookmarkable_class, int $bookmarkable_id): bool
    {
        $result = $this->findBookmarkable($bookmarkable_class, $bookmarkable_id);

        if (count($result) !== 0) return true;

        return false;
    }

    /**
     *  Method for bookmark a bookmarkable object
     *
     *  @param  Illuminate\Database\Eloquent\Model  $bookmarkable_object
     *  @return bool    return true if success otherwise false
     */
    public function bookmark(Model $bookmarkable_object): bool
    {
        $this->validateMethod();

        $bookmark = new Bookmark();

        $bookmark->bookmarkable()->associate($bookmarkable_object);
        // $this referred to the model
        $bookmark->bookmarker()->associate($this);

        event(new Bookmarked($bookmarkable_object));

        return $bookmark->save();
    }

    /**
     *  Method for remove bookmarked model
     * 
     *  @param  string  $bookmarkable_class
     *  @param  int     $bookmarkable_id
     * 
     *  @return bool
     */
    public function unBookmark(string $bookmarkable_class, int $bookmarkable_id): bool
    {
        $this->validateMethod();

        $bookmarkable = Bookmark::where([
            'bookmarkable_type' => $bookmarkable_class,
            'bookmarkable_id' => $bookmarkable_id,
        ])->first()->bookmarkable;

        $result = $this->bookmarks($bookmarkable_class)->detach($bookmarkable_id);

        if ($result >= 1) {
            event(new UnBookmark($bookmarkable));
            return true;
        }

        return false;
    }

    /**
     *  This method for get all bookmark in related object
     *
     *  @param  string  $bookmarkable_class
     *  @param  bool    $queryReturn determine whether the return value should raw query or collection model
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

        $tabel_name     =   config('bookmark.table_name', 'bookmarks');
        $bookmarker     =   config('bookmark.morph_name.bookmarker', 'bookmarker');
        $bookmarkable   =   config('bookmark.morph_name.bookmarkable', 'bookmarkable');

        return $this->morphToMany($bookmarkable_class, $bookmarker, $tabel_name, null, "{$bookmarkable}_id");
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
