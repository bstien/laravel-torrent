# Laravel Torrent

A package for [Laravel 5](http://laravel.com/) to scrape for torrents.

## Installation
Add this to your `composer.json`:
```json
"require": {
    "bstien/laravel-torrent": "dev-master"
}
```

Register the facade and ServiceProvider in `config/app.php`:
```php
'providers' => [
    // ...
    'Stien\Torrent\TorrentServiceProvider',
];

'aliases' => [
    // ...
    'Torrent    => 'Stien\Torrent\Facades\Torrent',
];
```

## Usage

### Regular search
Returns an array with `Stien\Torrent\Result\Torrent`-objects if matches are found. If not, an empty array is returned.
```php
use Stien\Torrent\Facades\Torrent;
# You can register this to your facades-array in config/app.php if you like

$torrents = Torrent::search("Modern Family");

foreach( $torrents as $torrent )
{
    echo $torrent->getTitle();
}


# To search within a specific category, use any of the constants in
# Stien\Torrent\Categories.
```

### Search in category
Include a category as the second argument to `Torrent::search()`. See constants in `Stien\Torrent\Categories` for reference.

It defaults to `Categories::ALL` if none are given.
```php
use Stien\Torrent\Facades\Torrent;
use Stien\Torrent\Categories as CAT;

$torrents = Torrent::search("Die Hard", CAT::MOVIES_HD);
```

## Implement your own adapter
To extend this package with another adapter, create a new class and have it implement `Stien\Torrent\TorrentAdapterInterface`.

Register your adapter with the scraper
```php
use Stien\Torrent\Facades\Torrent;

$myAdapter = new MyAdapter();
$myAdapter->setHttpClient(new \GuzzleHttp\Client);

Torrent::addAdapter( $myAdapter );
```
