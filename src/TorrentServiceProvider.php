<?php
namespace Stien\Torrent;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Stien\Torrent\Adapter\PirateBayAdapter;

class TorrentServiceProvider extends ServiceProvider {

	/**
	 * Boot the service provider.
	 *
	 * @return void
	 */
	public function boot()
	{
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('bstien.torrent.scraper', function ($app)
		{
			$torrentScraper = new TorrentScraper();

			// Add PirateBayAdapter
			$pirateBayAdapter = new PirateBayAdapter();
			$pirateBayAdapter->setHttpClient(new \GuzzleHttp\Client());
			$torrentScraper->addAdapter($pirateBayAdapter);

			return $torrentScraper;
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [];
	}
}