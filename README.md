# Laravel Torrent

A package for Laravel to scrape for torrents.

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
```php
use Stien\Torrent\Facades\Torrent;
# You can register this to your facades-array in config/app.php if you like

$torrents = Torrent::search("Modern Family");

foreach( $torrents as $torrent )
{
    echo $torrent->getTitle();
}
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
