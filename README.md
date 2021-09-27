# Logger - a simple package for laravel framework

> requirement: php ^7.2.5 & laravel 7

pastikan system anda memenuhi semua persyaratan di atas!

## Brief

Package ini memunkinkan anda melakukan bookmark terhadap model yang anda inginkan, tampa harus repot membuatnya dari awal!

## Instalation

Silahkan jalankan perintah dibawah, kami menganggap composer telah terinstal di mesin anda

    composer require cloudmyn/bookmark

## Usage

untuk tahap selanjutnya anda harus *published* vendor package seperti migrations dan config

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

Untuk mendapatkan model yang di bookmark:

```PHP
$user->getBookmarks();
```

Untuk melihat siapa saja yang mem-bookmark model post:

```PHP
$post->getBookmarkers();
```

Untuk menghapus model yang telah di-bookmark:

```PHP
// Method untuk di model user
$user->unBookmark($post);

// Method untuk di model post
$post->unBookmark($user);
```

Untuk menemukan object yang telah di-bookmark:

```PHP
// Method untuk di model user
$user->findBookmarkable($post);

// Method untuk di model post
$post->findBookmarker($user);
```

Untuk mengecek apakah object telah di bookmark atau tidak:

```PHP

$user->isBookmarked($post);

```