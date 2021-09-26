# Logger - a simple package for laravel framework

> requirement: php ^7.2.5 & laravel 7

pastikan system anda memenuhi semua persyaratan di atas!

## Brief

Package ini memunkinkan anda melakukan bookmark terhadap model yang anda inginkan, tampa harus repot membuatnya dari awal!

## Usage

Kami menganggap package ini sudah ter-install di mesin anda!, untuk tahap selanjutnya anda harus *published* vendor package seperti migrations dan config

    php artisan vendor:publish --provider="CloudMyn\Bookmark\BookmarkServiceProvider" --tag="config"
 
    php artisan vendor:publish --provider="CloudMyn\Bookmark\BookmarkServiceProvider" --tag="migrations"
 
Silahkan masukkan trait **CloudMyn\Bookmark\Traits\Bookmarker** di model user anda

```PHP
namespace App\Models\User;

use CloudMyn\Bookmark\Traits\Bookmarker;
    
class User extends Illuminate\Database\Eloquent\Model 
{
    use Bookmarker;

    ...
}
```

Begitupun dengan model yang ingin di bookmark, silahkan masukkan **CloudMyn\Bookmark\Traits\Bookmarkable** untuk menandainya

```PHP
namespace App\Models\Post;

use CloudMyn\Bookmark\Traits\Bookmarkable;
    
class Post extends Illuminate\Database\Eloquent\Model 
{
    use Bookmarkable;

    ...
}
```

Untuk melakukan Bookmark di sebuah model, silahkan masukkan code dibawah ini

```PHP
// Bookmark untuk di model user
$user->bookmark($post);

// Bookmark untuk di model post
$post->bookmark($user)
```

Untuk mendapatkan model yang di bookmark silahkan masukkan code di bawah ini

```PHP
$user->getBookmarks();
```

Untuk melihat siapa saja yang mem-bookmark model post silahkan masukkan code dibawah ini

```PHP
$post->getBookmarkers();
```
